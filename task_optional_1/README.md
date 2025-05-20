
# 🧠 Optional Task — Cross-Language Code Mirror (Advanced)

> ⚡ **Objective:**  
Create a program in one programming language that generates another program in a different language.  
That second program, when executed, must output the original code of the first.

This is an **optional challenge**, meant for students who want to push their creative and engineering skills to the next level.

---

## 🎯 What You Need to Do

1. Choose **two programming languages** from this list:
   - JavaScript
   - TypeScript
   - PHP
   - Python
   - Ruby
   - Java
   - C#
   - Rust

2. Write code in the **first language** that outputs **valid code** in the **second language**.

3. The second language program, when executed, should print the **original code** from the first file.

---

## 🔄 Goal Summary

You are creating a loop like this:

```
Language A → outputs → Language B code → which outputs → Language A code
```

🧠 It’s a kind of **cross-language quine** — a program that prints another, which then prints the first.

---

## 📜 Rules

| ✅ Must Do | ❌ Must Not Do |
|-----------|----------------|
| Use only **two languages** | ❌ Don’t create or write extra files |
| Keep the solution in **one single file** | ❌ Don’t use I/O to read your own source |
| Use **manual string crafting** | ❌ Don’t access source via reflection or meta-programming |
| Ensure the cycle works 100% | ❌ No file access, no external inputs |

---

## 📦 Output Demonstration (Example)

Suppose you write code in `JavaScript` (A) that outputs code in `Python` (B).

### What to demonstrate:

```bash
$ node solution.js > solution.py
$ python3 solution.py > result.js
$ diff solution.js result.js  # ✅ No difference — they match
```

✅ This proves the cycle: `JS ➝ PY ➝ JS`

You may reverse this direction or use other languages like `PHP ➝ Java ➝ PHP`, etc.

---

## 🎥 Submission Requirements

1. Upload the **source code file** (only one file).
2. Record a **video or screenshots** showing:
   - Running language A file and redirecting output to language B
   - Running language B file and getting back original code
   - Final `diff` or comparison of input vs. output

3. Submit the video/screenshots + code to:  
📧 `p.lebedev@itransition.com`

---

## 🚨 Rules Recap

- Your file **must not** use `readFile`, `fs`, or similar file reading
- You **cannot access** your source via reflection, `__file__`, etc.
- You **must not** use any API that gives access to the script body
- You **can** use tricks like escaping quotes, managing newlines, etc.

---

## 💡 Why This Task?

- It’s an advanced mental exercise in **self-replicating logic**
- Tests your **understanding of string construction** and **language syntax**
- Helps you develop **attention to detail** and **cross-language thinking**
- Useful for mastering **code generation** techniques

---

## 🗓️ Deadline

> No strict deadline — submit only if you feel ready  
This task is **entirely optional** and for **extra points** or fun.

---

## 🙋 FAQ

**Q: Can I just print the code by reading the source file?**  
**A:** ❌ No — that breaks the main rule. Your code must contain all needed strings.

**Q: Do I need to use specific languages?**  
**A:** ✅ Use any two from the provided list — your choice.

**Q: What if I can only do half?**  
**A:** Submit anyway! Even partial solutions may earn credit and show initiative.

---

## 🧠 Final Words

This task is meant to be fun, clever, and mind-bending. Don’t stress — it’s **not required**.

But if you complete it, you’ll prove you have strong fundamentals, creativity, and grit.

Good luck! 💡💪
