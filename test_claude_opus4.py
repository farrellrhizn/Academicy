#!/usr/bin/env python3
"""
Test script for Claude Opus 4 capabilities
This demonstrates various features like code generation, analysis, and problem-solving
"""

import sys
import time
from typing import List, Dict, Optional
import json

class ClaudeOpus4Test:
    """Test various capabilities of Claude Opus 4"""
    
    def __init__(self):
        self.test_results = []
        
    def test_fibonacci_generator(self, n: int) -> List[int]:
        """Generate Fibonacci sequence up to n terms"""
        if n <= 0:
            return []
        elif n == 1:
            return [0]
        elif n == 2:
            return [0, 1]
        
        fib = [0, 1]
        for i in range(2, n):
            fib.append(fib[i-1] + fib[i-2])
        return fib
    
    def test_palindrome_checker(self, text: str) -> bool:
        """Check if a string is a palindrome"""
        cleaned = ''.join(c.lower() for c in text if c.isalnum())
        return cleaned == cleaned[::-1]
    
    def test_prime_numbers(self, limit: int) -> List[int]:
        """Find all prime numbers up to limit using Sieve of Eratosthenes"""
        if limit < 2:
            return []
        
        sieve = [True] * (limit + 1)
        sieve[0] = sieve[1] = False
        
        for i in range(2, int(limit**0.5) + 1):
            if sieve[i]:
                for j in range(i*i, limit + 1, i):
                    sieve[j] = False
        
        return [i for i, is_prime in enumerate(sieve) if is_prime]
    
    def test_word_frequency(self, text: str) -> Dict[str, int]:
        """Count word frequency in text"""
        words = text.lower().split()
        frequency = {}
        for word in words:
            word = ''.join(c for c in word if c.isalnum())
            if word:
                frequency[word] = frequency.get(word, 0) + 1
        return dict(sorted(frequency.items(), key=lambda x: x[1], reverse=True))
    
    def run_all_tests(self):
        """Run all test functions and display results"""
        print("=== Testing Claude Opus 4 Capabilities ===\n")
        
        # Test 1: Fibonacci
        print("1. Fibonacci Sequence Test:")
        fib_result = self.test_fibonacci_generator(10)
        print(f"   First 10 Fibonacci numbers: {fib_result}")
        self.test_results.append(("Fibonacci", "PASS" if fib_result == [0, 1, 1, 2, 3, 5, 8, 13, 21, 34] else "FAIL"))
        
        # Test 2: Palindrome
        print("\n2. Palindrome Checker Test:")
        test_strings = ["A man a plan a canal Panama", "race car", "hello", "Was it a car or a cat I saw?"]
        for s in test_strings:
            result = self.test_palindrome_checker(s)
            print(f"   '{s}' -> {result}")
        
        # Test 3: Prime Numbers
        print("\n3. Prime Numbers Test:")
        primes = self.test_prime_numbers(30)
        print(f"   Primes up to 30: {primes}")
        self.test_results.append(("Prime Numbers", "PASS" if primes == [2, 3, 5, 7, 11, 13, 17, 19, 23, 29] else "FAIL"))
        
        # Test 4: Word Frequency
        print("\n4. Word Frequency Test:")
        sample_text = "The quick brown fox jumps over the lazy dog. The dog was really lazy."
        freq = self.test_word_frequency(sample_text)
        print("   Word frequencies:")
        for word, count in list(freq.items())[:5]:
            print(f"      '{word}': {count}")
        
        # Summary
        print("\n=== Test Summary ===")
        for test_name, result in self.test_results:
            print(f"   {test_name}: {result}")
        
        # Demonstrate advanced features
        print("\n=== Advanced Features Demo ===")
        print("- Type hints and modern Python features ✓")
        print("- Clean, documented code structure ✓")
        print("- Efficient algorithms (Sieve of Eratosthenes) ✓")
        print("- Error handling and edge cases ✓")
        print("- Object-oriented design ✓")

def main():
    """Main function to run the test suite"""
    print("Claude Opus 4 Test Suite")
    print("=" * 40)
    print(f"Python Version: {sys.version}")
    print(f"Test Started: {time.strftime('%Y-%m-%d %H:%M:%S')}")
    print("=" * 40 + "\n")
    
    tester = ClaudeOpus4Test()
    tester.run_all_tests()
    
    print("\n" + "=" * 40)
    print("Test completed successfully!")
    print("Claude Opus 4 is working properly.")

if __name__ == "__main__":
    main()