
# 🧩 Task #2 — SHA3-256 File Hash Challenge

> ⚡ **Objective:**  
Write a script (in any programming language) that reads **exactly 256 binary files**, computes their **SHA3-256** hashes, sorts and combines them in a specific way, and then outputs a **final hash** used for verification.

Unlike Task 1, here you only submit the **final result**, not your code.

---

## 📥 Input Format

- Download the archive from:  
  📦 https://www.dropbox.com/s/oy2668zp1lsuseh/task2.zip?dl=1
- Unzip the archive to get **256 binary files**.
- File content should be read **as binary**, not as text.

---

## 🧪 Your Task (Step-by-Step)

1. **For each of the 256 files:**
   - Read the file as **binary** (not string or text).
   - Compute its **SHA3-256 hash**.
   - Convert the hash to a **64-character lowercase hexadecimal string**.

2. **Sort all 256 hashes:**
   - As **full strings**, in **descending order**.
   - Not by byte values, but **as strings**.

3. **Join all sorted hashes:**
   - Concatenate them **into one single string**.
   - **No separators**, no commas, no line breaks — just one long string.

4. **Append your email (used during registration):**
   - Use your email in **all lowercase**.
   - Add it to the **end of the concatenated hash string**.

5. **Calculate the final SHA3-256 hash:**
   - Take the full long string (joined hashes + your lowercase email).
   - Compute the **SHA3-256 hash** of this string.
   - Convert it to **64-character lowercase hex**.

6. **Submit only that final 64-digit hex hash**:
   - Post it in the Discord `#🤖tasks-1-2` channel with this format:
     ```
     !task2 youremail@example.com abcdef1234...<64 hex digits>
     ```

---

## 🔒 Restrictions & Requirements

| ✅ You Must Do | ❌ You Must Not Do |
|---------------|-------------------|
| Use SHA3-256 hash only | ❌ Don’t use SHA-256, SHA1, or MD5 |
| Process exactly 256 files | ❌ Don’t include hidden/system files |
| Read files as binary | ❌ Don’t read files as UTF-8 or text |
| Sort full hash strings (descending) | ❌ Don’t sort by character-by-character or bytes |
| Join strings with no separator | ❌ Don’t add commas, spaces, or newlines |
| Append your lowercase email | ❌ Don’t use uppercase or mixed case emails |
| Hash the final string and submit it | ❌ Don’t submit the intermediate string or list |

---

## 📤 Output Format

Your final output should be:

- A **single 64-character hex string**
- In all **lowercase**
- Posted like this:
  ```
  !task2 youremail@example.com abcdefabcdefabcdefabcdefabcdefabcdefabcdefabcdefabcdefabcdefab
  ```

---

## 🧠 Example (Conceptual)

Let’s say you have 3 files (`a`, `b`, `c`) with hashes:

```
f4a1...  (from file a)
a8b2...  (from file b)
ff00...  (from file c)
```

Steps:
- Sort descending: `ff00...`, `f4a1...`, `a8b2...`
- Join: `"ff00...f4a1...a8b2..."`
- Append email: `"ff00...f4a1...a8b2...youremail@example.com"`
- Final hash = SHA3-256 of above string → `"d3e9a7..."`

Submit:
```
!task2 youremail@example.com d3e9a7...
```

---

## ⚠️ Warnings & Gotchas

- 🧪 Check that you’re using **SHA3-256**, not SHA-256.
- ⚠️ Some hash libraries default to SHA-256 — verify explicitly!
- 🧯 Don’t hash the **same object** multiple times — use fresh hash for each file.
- 🗃️ Ensure you’re only reading the **256 expected files**, nothing else.
- 🧵 Watch out for **Array.join()** behavior in JavaScript — don’t include separators.
- ✉️ Your email should be in **lowercase** — even one uppercase letter will fail the test.

---

## 📚 Language Tips

You may use **any language** you are comfortable with:

| Language | SHA3-256 Support |
|----------|------------------|
| JavaScript (Node.js ≥ 20) | `crypto.createHash('sha3-256')` |
| Python ≥ 3.6 | `hashlib.sha3_256()` |
| PHP 7.1+ | `hash('sha3-256', $binary)` |
| C# | Use `SHA3.Net` or custom implementation |
| Java | Use BouncyCastle or built-in via `MessageDigest` (check version) |

> Just remember: you're submitting only the result, not the code.

---

## 🗓️ Deadline

> **This Friday (inclusive)**  
You may submit multiple times, but **only the correct one counts**.

---

## 🙋 FAQ

**Q: Can I use SHA-256 instead of SHA3-256?**  
**A:** ❌ No — they are different algorithms. Use only SHA3-256.

**Q: What if I accidentally change the files?**  
**A:** Redownload the ZIP and avoid using text editors — binary files must remain untouched.

**Q: What if I submit the wrong result?**  
**A:** You can resubmit, but only the correct result will count.

---

## 🧠 Bonus Tip

This task simulates real-world file processing, checksum verification, and strict spec compliance — all skills needed in systems engineering, DevOps, and backend development.

Good luck! 💪
