import random


class GeneticOptimizer:
    def __init__(self, products, budget, population_size=50,
                 generations=350, mutation_rate=0.1, crossover_rate=0.7):
        self.products = products
        self.budget = budget
        self.population_size = population_size
        self.generations = generations
        self.mutation_rate = mutation_rate
        self.crossover_rate = crossover_rate
        self.population = self.init_population()

    def init_population(self):
        n = len(self.products)
        avg_price = sum(p['price'] for p in self.products) / n if n > 0 else 1
        # Probabilité adaptée au budget pour éviter les chromosomes vides ou trop pleins
        p_select = min(0.5, (self.budget / avg_price) / n)

        population = []
        for _ in range(self.population_size):
            chrom = [1 if random.random() < p_select else 0 for _ in range(n)]
            population.append(chrom)
        return population

    def calculate_fitness(self, chromosome):
        total_price = 0
        total_score = 0
        categories = set()

        for i, selected in enumerate(chromosome):
            if selected:
                total_price += self.products[i]['price']
                total_score += self.products[i]['score']
                categories.add(self.products[i]['category'])

        # Chromosome vide → inutile
        if total_price == 0:
            return 0

        # Pénalité stricte pour interdire le dépassement de budget
        if total_price > self.budget:
            # Léger gradient pour encourager la réduction du prix
            return 0.01 / total_price

        variety_bonus = len(categories) * 0.5
        # Léger bonus pour utiliser le budget efficacement (sans diviser par le prix)
        budget_usage_bonus = (total_price / self.budget) * 2

        return total_score + variety_bonus + budget_usage_bonus

    def selection(self, fitness_cache):
        # Tournoi sur un échantillon aléatoire (évite les recalculs)
        tournament = random.sample(self.population, min(3, len(self.population)))
        return max(tournament, key=lambda x: fitness_cache.get(id(x), 0))

    def crossover(self, parent1, parent2):
        # CORRIGÉ : < crossover_rate pour croiser 80% du temps (était > avant)
        if random.random() < self.crossover_rate:
            cp1 = random.randint(0, len(parent1) - 1)
            cp2 = random.randint(cp1, len(parent1) - 1)
            child1 = parent1[:cp1] + parent2[cp1:cp2] + parent1[cp2:]
            child2 = parent2[:cp1] + parent1[cp1:cp2] + parent2[cp2:]
            return child1, child2
        return parent1[:], parent2[:]

    def mutate(self, chromosome):
        for i in range(len(chromosome)):
            if random.random() < self.mutation_rate:
                chromosome[i] = 1 - chromosome[i]

    def run(self):
        history = []
        best_overall = None
        best_fitness_overall = -1
        stagnation = 0

        for gen in range(self.generations):
            # Cache fitness pour cette génération (évite les recalculs redondants)
            fitness_cache = {id(ind): self.calculate_fitness(ind)
                             for ind in self.population}

            best_in_gen = max(self.population, key=lambda x: fitness_cache[id(x)])
            best_fitness_in_gen = fitness_cache[id(best_in_gen)]

            if best_fitness_in_gen > best_fitness_overall:
                best_fitness_overall = best_fitness_in_gen
                best_overall = best_in_gen[:]
                stagnation = 0
            else:
                stagnation += 1

            history.append(best_fitness_in_gen)

            # Arrêt anticipé si stagnation prolongée (augmenté pour une meilleure optimisation)
            if stagnation >= 100:
                break

            new_population = [best_in_gen[:]]  # Élitisme : garde le meilleur

            while len(new_population) < self.population_size:
                p1 = self.selection(fitness_cache)
                p2 = self.selection(fitness_cache)
                c1, c2 = self.crossover(p1, p2)
                self.mutate(c1)
                self.mutate(c2)
                new_population.append(c1)
                if len(new_population) < self.population_size:
                    new_population.append(c2)

            self.population = new_population

        # Résultat final
        selected_products = [self.products[i]
                              for i, s in enumerate(best_overall) if s]
        total_price = sum(p['price'] for p in selected_products)
        total_score = sum(p['score'] for p in selected_products)

        return {
            "best_combination": selected_products,
            "total_price": total_price,
            "total_score": total_score,
            "budget": self.budget,
            "history": history
        }