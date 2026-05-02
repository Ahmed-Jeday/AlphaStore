"""
PC Component Compatibility CSP Solver
Given a set of already-selected components, returns valid domains (compatible components)
for every other category. Incompatible components are excluded from the returned lists.
"""


class PCCompatibilityCSP:
    """
    Constraint Satisfaction Problem solver for PC building.
    At each user selection, this solver re-computes which components
    in other categories are still valid (compatible).
    """

    # Form factors supported by each case type
    CASE_SUPPORTS = {
        'ATX': ['ATX', 'mATX', 'ITX'],
        'mATX': ['mATX', 'ITX'],
        'ITX': ['ITX'],
    }

    def __init__(self, all_components: list[dict], selected: dict, budget: float):
        """
        :param all_components: Full list of all pc_components from DB.
        :param selected: Dict mapping component_type -> component dict already chosen by user.
                         Example: {'cpu': {...}, 'motherboard': {...}}
        :param budget: User's total budget in euros.
        """
        self.all_components = all_components
        self.selected = selected  # {type: component_dict}
        self.budget = budget

        # Group all components by type for fast lookup
        self.by_type: dict[str, list[dict]] = {}
        for comp in all_components:
            t = comp['component_type']
            self.by_type.setdefault(t, [])
            self.by_type[t].append(comp)

    # ------------------------------------------------------------------
    # Individual constraint checks
    # ------------------------------------------------------------------

    def _socket_ok(self, comp: dict) -> bool:
        """CPU socket must match motherboard socket."""
        cpu = self.selected.get('cpu')
        mobo = self.selected.get('motherboard')

        if comp['component_type'] == 'cpu' and mobo:
            return comp['socket'] == mobo['socket']
        if comp['component_type'] == 'motherboard' and cpu:
            return comp['socket'] == cpu['socket']
        return True

    def _ram_type_ok(self, comp: dict) -> bool:
        """RAM type must match motherboard RAM type."""
        mobo = self.selected.get('motherboard')

        if comp['component_type'] == 'ram' and mobo:
            return comp['ram_type'] == mobo['ram_type']
        if comp['component_type'] == 'motherboard':
            ram = self.selected.get('ram')
            if ram:
                return comp['ram_type'] == ram['ram_type']
        return True

    def _ram_slots_ok(self, comp: dict) -> bool:
        """Number of RAM modules must not exceed motherboard slots."""
        mobo = self.selected.get('motherboard')
        ram = self.selected.get('ram')

        if comp['component_type'] == 'ram' and mobo:
            slots = mobo.get('ram_slots') or 4
            modules = comp.get('ram_modules') or 2
            return modules <= slots
        if comp['component_type'] == 'motherboard' and ram:
            slots = comp.get('ram_slots') or 4
            modules = ram.get('ram_modules') or 2
            return modules <= slots
        return True

    def _form_factor_ok(self, comp: dict) -> bool:
        """Case must support the motherboard form factor, and vice versa."""
        mobo = self.selected.get('motherboard')
        case = self.selected.get('case')

        if comp['component_type'] == 'case' and mobo:
            mobo_ff = mobo.get('form_factor') or 'ATX'
            case_ff = comp.get('form_factor') or 'ATX'
            supported = self.CASE_SUPPORTS.get(case_ff, ['ATX', 'mATX', 'ITX'])
            return mobo_ff in supported

        if comp['component_type'] == 'motherboard' and case:
            mobo_ff = comp.get('form_factor') or 'ATX'
            case_ff = case.get('form_factor') or 'ATX'
            supported = self.CASE_SUPPORTS.get(case_ff, ['ATX', 'mATX', 'ITX'])
            return mobo_ff in supported

        return True

    def _gpu_clearance_ok(self, comp: dict) -> bool:
        """GPU physical length must fit inside the case."""
        case = self.selected.get('case')
        gpu = self.selected.get('gpu')

        if comp['component_type'] == 'gpu' and case:
            max_len = case.get('gpu_max_length')
            gpu_len = comp.get('gpu_length')
            if max_len and gpu_len:
                return int(gpu_len) <= int(max_len)
        if comp['component_type'] == 'case' and gpu:
            max_len = comp.get('gpu_max_length')
            gpu_len = gpu.get('gpu_length')
            if max_len and gpu_len:
                return int(gpu_len) <= int(max_len)
        return True

    def _psu_ok(self, comp: dict) -> bool:
        """PSU wattage must cover total TDP * 1.2 safety margin."""
        # Calculate current TDP from selected components
        current_tdp = sum(
            int(c.get('tdp') or 0)
            for t, c in self.selected.items()
            if t not in ('psu', 'case', 'storage', 'ram')
        )

        if comp['component_type'] == 'psu':
            # Check if this PSU can handle current + new TDP
            new_tdp = current_tdp
            wattage = comp.get('wattage') or 0
            return int(wattage) >= new_tdp * 1.2

        if comp['component_type'] in ('cpu', 'gpu'):
            psu = self.selected.get('psu')
            if psu:
                comp_tdp = int(comp.get('tdp') or 0)
                wattage = int(psu.get('wattage') or 0)
                return wattage >= (current_tdp + comp_tdp) * 1.2
        return True

    def _budget_ok(self, comp: dict) -> bool:
        """Adding this component must not exceed the total budget."""
        spent = sum(float(c.get('price') or 0) for c in self.selected.values())
        remaining = self.budget - spent
        return float(comp.get('price') or 0) <= remaining

    def _stock_ok(self, comp: dict) -> bool:
        return int(comp.get('stock') or 0) > 0

    # ------------------------------------------------------------------
    # Main solver
    # ------------------------------------------------------------------

    def is_compatible(self, comp: dict) -> dict:
        """
        Returns {'ok': bool, 'reason': str|None} for a given candidate component.
        """
        if not self._stock_ok(comp):
            return {'ok': False, 'reason': 'Out of stock'}
        if not self._budget_ok(comp):
            return {'ok': False, 'reason': 'Exceeds remaining budget'}
        if not self._socket_ok(comp):
            return {'ok': False, 'reason': 'Incompatible CPU socket'}
        if not self._ram_type_ok(comp):
            return {'ok': False, 'reason': 'Incompatible RAM type (DDR4/DDR5)'}
        if not self._ram_slots_ok(comp):
            return {'ok': False, 'reason': 'Not enough RAM slots'}
        if not self._form_factor_ok(comp):
            return {'ok': False, 'reason': 'Form factor incompatible with case'}
        if not self._gpu_clearance_ok(comp):
            return {'ok': False, 'reason': 'GPU too long for this case'}
        if not self._psu_ok(comp):
            return {'ok': False, 'reason': 'PSU wattage insufficient'}
        return {'ok': True, 'reason': None}

    def get_valid_domains(self) -> dict:
        """
        For each component type, return a list of components annotated with
        compatibility status (ok=True/False) and the reason for incompatibility.

        Returns:
            {
              'cpu': [{'component': {...}, 'ok': True, 'reason': None}, ...],
              'gpu': [...],
              ...
            }
        """
        TYPES = ['cpu', 'gpu', 'motherboard', 'ram', 'psu', 'storage', 'case']
        result = {}

        for t in TYPES:
            components = self.by_type.get(t, [])
            annotated = []
            for comp in components:
                # Skip already selected component (show it as selected)
                if self.selected.get(t) and self.selected[t]['id'] == comp['id']:
                    annotated.append({'component': comp, 'ok': True, 'reason': None, 'selected': True})
                    continue
                status = self.is_compatible(comp)
                annotated.append({
                    'component': comp,
                    'ok': status['ok'],
                    'reason': status['reason'],
                    'selected': False
                })
            result[t] = annotated

        return result
