import collections
import heapq

class SearchAlgorithms:
    @staticmethod
    def bfs(graph, start_node):
        """Breadth-First Search for product recommendations"""
        visited = set()
        queue = collections.deque([start_node])
        visited.add(start_node)
        result = []
        
        while queue:
            node = queue.popleft()
            result.append(node)
            for neighbor in graph.get(node, []):
                if neighbor not in visited:
                    visited.add(neighbor)
                    queue.append(neighbor)
        return result

    @staticmethod
    def dfs(graph, start_node):
        """Depth-First Search for deep exploration"""
        visited = set()
        result = []
        
        def _dfs(node):
            visited.add(node)
            result.append(node)
            for neighbor in graph.get(node, []):
                if neighbor not in visited:
                    _dfs(neighbor)
        
        _dfs(start_node)
        return result

    @staticmethod
    def a_star(graph, start, goal, h_func):
        """A* Search for optimal pathfinding in products"""
        open_set = []
        heapq.heappush(open_set, (0, start))
        came_from = {}
        g_score = {node: float('inf') for node in graph}
        g_score[start] = 0
        f_score = {node: float('inf') for node in graph}
        f_score[start] = h_func(start, goal)

        while open_set:
            current = heapq.heappop(open_set)[1]
            if current == goal:
                path = []
                while current in came_from:
                    path.append(current)
                    current = came_from[current]
                return path[::-1]

            for neighbor, weight in graph.get(current, []):
                tentative_g_score = g_score[current] + weight
                if tentative_g_score < g_score[neighbor]:
                    came_from[neighbor] = current
                    g_score[neighbor] = tentative_g_score
                    f_score[neighbor] = g_score[neighbor] + h_func(neighbor, goal)
                    heapq.heappush(open_set, (f_score[neighbor], neighbor))
        return None

class GameTheoryAlgorithms:
    @staticmethod
    def alpha_beta(node, depth, alpha, beta, maximizing_player):
        """Minimax with Alpha-Beta Pruning for price negotiation"""
        if depth == 0 or node.is_terminal():
            return node.value()

        if maximizing_player:
            value = -float('inf')
            for child in node.children():
                value = max(value, GameTheoryAlgorithms.alpha_beta(child, depth - 1, alpha, beta, False))
                alpha = max(alpha, value)
                if alpha >= beta:
                    break
            return value
        else:
            value = float('inf')
            for child in node.children():
                value = min(value, GameTheoryAlgorithms.alpha_beta(child, depth - 1, alpha, beta, True))
                beta = min(beta, value)
                if alpha >= beta:
                    break
            return value
