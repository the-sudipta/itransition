
# 🧩 Task #1 — Longest Common Substring (LCS)

> ⚡ **Objective:**  
Write a JavaScript program that finds the **longest common substring** shared among all input strings passed via **command-line arguments**.  
Your solution should be **minimal in file size** and run under **pure Node.js**.

---

## 📥 Input Format

- The input strings are provided through **command-line arguments**, e.g., `process.argv`.
- You will receive **0 or more** strings.
- Each string contains only **English letters and digits**.
- Length of each string: **≤ 256 characters**
- Number of strings: **≤ 64**

---

## 📤 Output Format

- Print **one** of the **longest substrings** found **in all input strings**, followed by a **newline**.
- If **no common substring** exists, or **no arguments** are passed, print a **single newline**.
- ⚠️ **Important:**  
  - Do **not** print any extra characters.
  - Do **not** print debug info, logs, or error messages.

---

## 📌 Examples

```bash
> node lcs.js ABCDEFZ WBCDXYZ
BCD

> node lcs.js 132 12332 12312
1

> node lcs.js ABCDEFGH ABCDEFG ABCEDF ABCED
ABC

> node lcs.js ABCDEFGH
ABCDEFGH

> node lcs.js
<prints a blank line>
```

---

## 🔒 Restrictions

| 🔒 Rule | 🚫 Not Allowed |
|--------|----------------|
| 💾 File name must be | `lcs.js` |
| 📦 External libraries | ❌ No `require`, `import`, or NPM packages |
| 📤 Input reading | ❌ No `readline`, `process.stdin`, or file reading |
| 📡 External access | ❌ No network, files, or APIs |
| 🚫 Exiting | ❌ Avoid `process.exit(code !== 0)` |
| 🧪 Empty input | Must not crash if no arguments are passed |
| 📤 Output | Must be clean — only the required substring and newline |

---

## 📏 Code Size Limitations

### 📦 Your solution will be graded **by the size of your `lcs.js` file in bytes**.

> ✅ The **smaller** your `.js` file, the **better** your score.

| File Size (bytes) | Grade |
|------------------|-------|
| **400+**         | Poor — no optimization 😢 |
| **300–399**      | Acceptable 👍 |
| **200–299**      | Good 👌 |
| **150–199**      | Very Good ✅ |
| **120–149**      | Excellent 💪 |
| **< 120**        | 🔥 Outstanding (code golf master!) |

> 📝 Use short variable names, no comments, no indentation, and minimal logic.

---

## 📦 Submission Instructions

1. Save your working solution as a file named **`lcs.js`**.
2. Drag & drop the file into the Discord channel: `#🤖tasks-1-2`.
3. In the **same message**, paste this line:
```
!task1 your@email.com
```

> 🧪 The system will automatically test your solution.

---

## ⚠️ Warnings & Edge Cases

- Test this input before submitting:
```bash
node lcs.js ABCQEFDEFGHIJ BCXEFGYZBCDEWEFGHU
```
✅ Should output `EFGH`.

- Your script **must not fail** if no arguments are passed:
```bash
node lcs.js
```
✅ Should output a **blank line**, no error.

- Output must be **exact**. Extra characters or logs will **fail the test**.

---

## 🎯 Why This Task?

- JavaScript is everywhere — you'll use it in real jobs.
- Writing **short, efficient code** teaches deep understanding.
- You’ll **learn how to adapt** to strict client-like requirements.
- You'll practice solving a classic algorithm problem — useful for interviews.

---

## ⏰ Deadline

> **This Wednesday (inclusive)**  
You may submit **multiple times**, but **only the best working version will be recorded**.

---

## 🙋 FAQ

**Q: Can I use `fs`, `http`, or any packages?**  
**A:** ❌ No. Only use built-in `process.argv` — nothing else.

**Q: Can I return multiple substrings if there are several?**  
**A:** No — return **just one** of the longest substrings.

**Q: What if I don’t optimize the code?**  
**A:** Your solution may pass tests, but get a poor score due to file size. Try to shrink it!

---

## 🧠 Bonus Tip

Think of this task as a **real job test**.  
Can you write compact, working code that meets **very strict requirements**?

If yes, you're on the path to becoming a true software engineer.

Good luck! 💪

