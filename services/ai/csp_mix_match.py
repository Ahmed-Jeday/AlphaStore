import random

class ColorCompatibility:
    # NEUTRAL COLORS can go with almost anything
    NEUTRAL_COLORS = {"Blanc", "Noir", "Gris chiné", "Gris rayé", "Beige", "Beige clair", "Beige naturel", "Argent", "Graphite", "Doré"}
    
    # Simple compatibility map for non-neutral colors
    COMPATIBILITY_MATRIX = {
        "Jaune": ["Noir", "Blanc", "Bleu denim", "Gris chiné", "Marron", "Gris-vert"],
        "Jaune pastel": ["Blanc", "Beige", "Bleu clair", "Gris chiné", "Violet"],
        "Bleu denim": ["Blanc", "Noir", "Jaune", "Rouge", "Gris chiné", "Marron", "Beige"],
        "Bleu clair": ["Blanc", "Beige", "Jaune pastel", "Gris chiné", "Rose fuchsia"],
        "Bleu": ["Blanc", "Noir", "Beige", "Argent", "Doré"],
        "Rose fuchsia": ["Noir", "Blanc", "Gris chiné", "Beige", "Bleu clair"],
        "Marron": ["Beige", "Blanc", "Noir", "Bleu denim", "Jaune"],
        "Marron foncé": ["Beige", "Blanc", "Jaune"],
        "Gris-vert": ["Blanc", "Noir", "Beige", "Jaune"],
        "Rouge": ["Noir", "Blanc", "Bleu denim", "Gris chiné", "Doré"],
        "Violet": ["Noir", "Blanc", "Argent", "Jaune pastel"],
        "Doré": ["Noir", "Blanc", "Rouge", "Bleu", "Marron"]
    }

    @staticmethod
    def are_compatible(c1, c2):
        if not c1 or not c2: return True
        if c1 == c2: return True
        
        # Handle compound colors (e.g., "Noir et Blanc")
        # Normalize and split
        colors1 = [c.strip().capitalize() for c in str(c1).replace(' et ', ',').split(',')]
        colors2 = [c.strip().capitalize() for c in str(c2).replace(' et ', ',').split(',')]
        
        # If any part is neutral, it's generally compatible
        if any(c in ColorCompatibility.NEUTRAL_COLORS for c in colors1) or \
           any(c in ColorCompatibility.NEUTRAL_COLORS for c in colors2):
            return True
            
        # Check compatibility between any pair of components
        for part1 in colors1:
            for part2 in colors2:
                if part1 == part2: return True
                if part1 in ColorCompatibility.COMPATIBILITY_MATRIX:
                    if part2 in ColorCompatibility.COMPATIBILITY_MATRIX[part1]: return True
                if part2 in ColorCompatibility.COMPATIBILITY_MATRIX:
                    if part1 in ColorCompatibility.COMPATIBILITY_MATRIX[part2]: return True
                    
        # If we have unknown colors in both, assume compatible to avoid empty results
        all_known = all(c in ColorCompatibility.COMPATIBILITY_MATRIX or c in ColorCompatibility.NEUTRAL_COLORS for c in colors1 + colors2)
        if not all_known:
            return True
            
        return False

class SeasonConstraint:
    @staticmethod
    def are_compatible(s1, s2):
        if not s1 or not s2: return True
        s1, s2 = str(s1).lower(), str(s2).lower()
        if s1 in ['toutes_saisons', 'all', 'any', 'none', 'null', ''] or \
           s2 in ['toutes_saisons', 'all', 'any', 'none', 'null', '']:
            return True
        return s1 == s2

class OutfitCSP:
    def __init__(self, anchor_product, all_products, constraints=None):
        self.anchor = anchor_product
        self.all_products = all_products
        self.constraints = constraints or {}
        self.variables = []
        self.domains = {}
        self.solutions = []
        
        self.define_variables()
        self.define_domains()

    def define_variables(self):
        anchor_type = self.anchor['product_type']
        
        if anchor_type == 'ensemble':
            self.variables = ['chaussure', 'accessoire']
        elif anchor_type == 'haut':
            self.variables = ['bas', 'chaussure', 'accessoire']
        elif anchor_type == 'bas':
            self.variables = ['haut', 'chaussure', 'accessoire']
        elif anchor_type == 'chaussure':
            self.variables = ['haut', 'bas', 'accessoire']
        elif anchor_type == 'accessoire':
            self.variables = ['haut', 'bas', 'chaussure']
        else:
            self.variables = ['haut', 'bas', 'chaussure', 'accessoire']

    def define_domains(self):
        active_variables = []
        for var in self.variables:
            # Filter products by type and basic constraints (stock, gender)
            target_types = [var]

            domain = [
                p for p in self.all_products 
                if p['product_type'] in target_types 
                and p['stock'] > 0
                and p['id'] != self.anchor['id']
            ]
            
            # Apply initial filters (Season, Gender/Category if provided)
            target_season = self.constraints.get('season')
            if target_season and target_season not in ['toutes_saisons', 'Weather', 'null', None]:
                domain = [p for p in domain if SeasonConstraint.are_compatible(p['season'], target_season)]
            
            # Ensure compatibility with anchor season
            domain = [p for p in domain if SeasonConstraint.are_compatible(p['season'], self.anchor['season'])]
            
            # Category constraint:
            # 1=Femme, 2=Homme, 3=Enfant, 4=Accessoires
            anchor_cat = self.anchor['category_id']
            
            if var == 'accessoire':
                # Accessories can be anything, but prefer Cat 4 (neutral) or match anchor
                domain = [p for p in domain if p['category_id'] == 4 or p['category_id'] == anchor_cat]
            else:
                # Clothes and shoes must strictly match gendered category if anchor is gendered
                if anchor_cat in [1, 2, 3]:
                    domain = [p for p in domain if p['category_id'] == anchor_cat]
            
            # Prune domain by budget early to avoid confusing logs
            remaining_budget = self.constraints.get('budget', 300) - self.anchor['price']
            domain = [p for p in domain if p['price'] <= remaining_budget]
            
            if len(domain) > 0:
                # Shuffle domain for variety
                random.shuffle(domain)
                self.domains[var] = domain
                active_variables.append(var)
                print(f"DEBUG: Domain for {var}: found {len(domain)} items")
            else:
                print(f"DEBUG: Domain for {var}: EMPTY. Skipping this variable.")

        # Update variables to only include those with items
        self.variables = active_variables

    def is_consistent(self, var, value, assignment):
        # 1. Budget constraint
        current_total = self.anchor['price'] + value['price']
        for assigned_val in assignment.values():
            current_total += assigned_val['price']
        
        # Look-ahead: be very gentle with the estimate to avoid over-pruning
        remaining_vars_count = len(self.variables) - len(assignment) - 1
        budget = self.constraints.get('budget', 300)
        
        if current_total + (remaining_vars_count * 2) > budget:
            print(f"DEBUG: Consistency Fail (Budget): {current_total} + {remaining_vars_count*2} > {budget}")
            return False
            
        # 2. Color compatibility with anchor
        if not ColorCompatibility.are_compatible(self.anchor['color_name'], value['color_name']):
            print(f"DEBUG: Consistency Fail (Color-Anchor): {self.anchor['color_name']} vs {value['color_name']}")
            return False
            
        # 3. Color compatibility with other assigned items
        for assigned_val in assignment.values():
            if not ColorCompatibility.are_compatible(assigned_val['color_name'], value['color_name']):
                print(f"DEBUG: Consistency Fail (Color-Assigned): {assigned_val['color_name']} vs {value['color_name']}")
                return False
                
        # 4. Season consistency
        for assigned_val in assignment.values():
            if not SeasonConstraint.are_compatible(assigned_val['season'], value['season']):
                print(f"DEBUG: Consistency Fail (Season): {assigned_val['season']} vs {value['season']}")
                return False
                
        # 5. Genre (Category) consistency
        anchor_cat = self.anchor['category_id']
        # If anchor is gendered (1, 2, 3), and value is not an accessory, it must match
        if anchor_cat in [1, 2, 3] and value['product_type'] != 'accessoire':
            if value['category_id'] != anchor_cat:
                print(f"DEBUG: Consistency Fail (Gender-Anchor): {anchor_cat} vs {value['category_id']}")
                return False
                
        # Also ensure consistent gender between all assigned clothes
        for assigned_val in assignment.values():
            if assigned_val['product_type'] != 'accessoire' and value['product_type'] != 'accessoire':
                if assigned_val['category_id'] != value['category_id']:
                    print(f"DEBUG: Consistency Fail (Gender-Assigned): {assigned_val['category_id']} vs {value['category_id']}")
                    return False
                
        return True

    def backtrack_search(self, assignment):
        if len(assignment) == len(self.variables):
            # Calculate total and score for the solution
            items = [self.anchor] + list(assignment.values())
            total = sum(p['price'] for p in items)
            
            # Improved multi-criteria scoring
            score = 100
            
            # 1. Full outfit bonus
            clothing_count = len([p for p in items if p['product_type'] in ['haut', 'bas', 'ensemble', 'chaussure']])
            if clothing_count >= 3: score += 30
            
            # 2. Color harmony bonus
            colors = [p['color_name'] for p in items if p['color_name']]
            unique_colors = set(colors)
            if len(unique_colors) == 1:
                score += 15  # Monochromatic bonus
            elif len(unique_colors) == 2:
                score += 10  # Simple color palette
                
            # 3. Budget optimization
            budget = self.constraints.get('budget', 250)
            if total <= budget * 0.8:
                score += 10 # "Good deal" bonus
            
            # Check if we already have this solution (combination-wise)
            item_ids = sorted([p['id'] for p in items])
            for sol in self.solutions:
                if sorted([p['id'] for p in sol['items']]) == item_ids:
                    return

            self.solutions.append({
                "items": items,
                "total": round(total, 2),
                "score": score
            })
            return

        var = self.variables[len(assignment)]
        
        # Backtracking with basic Forward Checking (pruning)
        for value in self.domains[var]:
            if self.is_consistent(var, value, assignment):
                assignment[var] = value
                
                # Check if future variables still have non-empty domains
                if self.forward_check(assignment, len(assignment)):
                    self.backtrack_search(assignment)
                
                if len(self.solutions) >= self.constraints.get('max_solutions', 10):
                    return
                
                del assignment[var]

    def forward_check(self, assignment, current_var_index):
        # Improved Forward Check: Check ALL remaining variables
        for i in range(current_var_index + 1, len(self.variables)):
            next_var = self.variables[i]
            any_valid = False
            for value in self.domains[next_var]:
                if self.is_consistent(next_var, value, assignment):
                    any_valid = True
                    break
            if not any_valid:
                return False # Prune this branch
        return True

    def solve(self, max_solutions=5):
        self.constraints['max_solutions'] = max_solutions
        self.backtrack_search({})
        
        print(f"DEBUG: Search finished. Found {len(self.solutions)} solutions.")
        
        # Sort solutions by score descending
        self.solutions.sort(key=lambda x: x['score'], reverse=True)
        return self.solutions[:max_solutions]
