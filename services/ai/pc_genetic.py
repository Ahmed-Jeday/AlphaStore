"""
PC Genetic Algorithm — Recommends best components for unselected categories
given already-fixed selections and a filtered domain from the CSP.
"""
import random


# Usage-profile weight maps: {component_type: weight}
PROFILE_WEIGHTS = {
    'gaming': {
        'cpu': 0.30,
        'gpu': 0.40,
        'ram': 0.10,
        'motherboard': 0.05,
        'psu': 0.05,
        'storage': 0.05,
        'case': 0.05,
    },
    'workstation': {
        'cpu': 0.40,
        'gpu': 0.15,
        'ram': 0.30,
        'motherboard': 0.05,
        'psu': 0.05,
        'storage': 0.04,
        'case': 0.01,
    },
    'budget': {
        'cpu': 0.20,
        'gpu': 0.30,
        'ram': 0.15,
        'motherboard': 0.10,
        'psu': 0.10,
        'storage': 0.10,
        'case': 0.05,
    },
    'streaming': {
        'cpu': 0.35,
        'gpu': 0.30,
        'ram': 0.20,
        'motherboard': 0.05,
        'psu': 0.05,
        'storage': 0.04,
        'case': 0.01,
    },
}


class PCRecommendationGA:
    """
    Given:
      - valid_domains: {type: [component_dict, ...]}   (only compatible components)
      - fixed: {type: component_dict}                  (already chosen by user)
      - budget: remaining budget after fixed components
      - usage_profile: 'gaming' | 'workstation' | 'budget' | 'streaming'

    Finds the best combination for the remaining (non-fixed) categories.
    """

    def __init__(
        self,
        valid_domains: dict,
        fixed: dict,
        remaining_budget: float,
        usage_profile: str = 'gaming',
        population_size: int = 60,
        generations: int = 200,
        mutation_rate: float = 0.15,
        crossover_rate: float = 0.75,
    ):
        self.fixed = fixed
        self.remaining_budget = remaining_budget
        self.usage_profile = usage_profile if usage_profile in PROFILE_WEIGHTS else 'gaming'
        self.weights = PROFILE_WEIGHTS[self.usage_profile]
        self.population_size = population_size
        self.generations = generations
        self.mutation_rate = mutation_rate
        self.crossover_rate = crossover_rate

        # Only optimise for types NOT already fixed
        self.open_types = [t for t in valid_domains if t not in fixed]
        self.domains = {t: valid_domains[t] for t in self.open_types if valid_domains.get(t)}

        # Remove empty domains
        self.open_types = [t for t in self.open_types if self.domains.get(t)]

        self.population = self._init_population()

    # ------------------------------------------------------------------
    # Population initialisation
    # ------------------------------------------------------------------

    def _random_chromosome(self) -> list[int]:
        """Random index for each open type domain."""
        return [random.randint(0, len(self.domains[t]) - 1) for t in self.open_types]

    def _init_population(self) -> list[list[int]]:
        if not self.open_types:
            return []
        return [self._random_chromosome() for _ in range(self.population_size)]

    # ------------------------------------------------------------------
    # Fitness
    # ------------------------------------------------------------------

    def _decode(self, chromosome: list[int]) -> dict:
        """Return {type: component_dict} for the chromosome."""
        return {
            t: self.domains[t][chromosome[i]]
            for i, t in enumerate(self.open_types)
        }

    def calculate_fitness(self, chromosome: list[int]) -> float:
        decoded = self._decode(chromosome)

        total_price = sum(float(c.get('price') or 0) for c in decoded.values())
        if total_price > self.remaining_budget:
            # Hard penalty for over-budget
            return 0.01 / (total_price - self.remaining_budget + 1)

        # Weighted performance score
        perf = 0.0
        for t, comp in decoded.items():
            score = float(comp.get('performance_score') or 50)
            w = self.weights.get(t, 0.1)
            perf += score * w

        # Budget-utilisation bonus (reward spending close to budget)
        if self.remaining_budget > 0:
            utilisation = total_price / self.remaining_budget
            budget_bonus = utilisation * 5  # max +5
        else:
            budget_bonus = 0

        return perf + budget_bonus

    # ------------------------------------------------------------------
    # Genetic operators
    # ------------------------------------------------------------------

    def _select(self, fitness_cache: dict) -> list[int]:
        """Tournament selection (size 3)."""
        sample = random.sample(self.population, min(3, len(self.population)))
        return max(sample, key=lambda x: fitness_cache.get(id(x), 0))[:]

    def _crossover(self, p1, p2):
        if random.random() < self.crossover_rate and len(p1) > 1:
            pt = random.randint(1, len(p1) - 1)
            return p1[:pt] + p2[pt:], p2[:pt] + p1[pt:]
        return p1[:], p2[:]

    def _mutate(self, chromosome):
        for i, t in enumerate(self.open_types):
            if random.random() < self.mutation_rate:
                chromosome[i] = random.randint(0, len(self.domains[t]) - 1)

    # ------------------------------------------------------------------
    # Main loop
    # ------------------------------------------------------------------

    def run(self) -> dict:
        if not self.open_types or not self.population:
            return {
                'recommended': {},
                'total_recommended_price': 0,
                'fitness_history': [],
                'profile': self.usage_profile,
            }

        best_chromosome = None
        best_fitness = -1
        history = []
        stagnation = 0

        for _ in range(self.generations):
            fitness_cache = {id(ind): self.calculate_fitness(ind) for ind in self.population}

            gen_best = max(self.population, key=lambda x: fitness_cache[id(x)])
            gen_best_f = fitness_cache[id(gen_best)]

            if gen_best_f > best_fitness:
                best_fitness = gen_best_f
                best_chromosome = gen_best[:]
                stagnation = 0
            else:
                stagnation += 1

            history.append(round(gen_best_f, 4))

            if stagnation >= 50:
                break

            new_pop = [gen_best[:]]  # elitism
            while len(new_pop) < self.population_size:
                p1 = self._select(fitness_cache)
                p2 = self._select(fitness_cache)
                c1, c2 = self._crossover(p1, p2)
                self._mutate(c1)
                self._mutate(c2)
                new_pop.append(c1)
                if len(new_pop) < self.population_size:
                    new_pop.append(c2)
            self.population = new_pop

        if best_chromosome is None:
            best_chromosome = self.population[0]

        recommended = self._decode(best_chromosome)
        total_price = sum(float(c.get('price') or 0) for c in recommended.values())

        return {
            'recommended': {t: comp for t, comp in recommended.items()},
            'total_recommended_price': round(total_price, 2),
            'fitness_history': history,
            'profile': self.usage_profile,
        }
