# 🧩 Task #3 — Generalized Non-Transitive Dice Game (Secure & Fair)

> ⚡ **Objective:**  
Implement a console application that models a **non-transitive dice** game with **provably fair** random generation.  
Supports any number of dice (>2), each with arbitrary face values.

---

## 📥 Input Format

- Launched via CLI with **3 or more** dice definitions:  
  ```
  program <dice1> <dice2> <dice3> [...]
  ```
- Each `<dice>` is a comma-separated list of **6 integers** (faces):  
  ```
  2,2,4,4,9,9
  6,8,1,1,8,6
  7,5,3,7,5,3
  ```
- You may support other face counts (e.g., 4, 20), but **6** is recommended.

---

## ❌ Error Handling

If arguments are invalid, print a **clear error** (no stacktrace) and an example:

- **Too few dice** (e.g., 2):  
  ```
  ERROR: Provided 2 dice; at least 3 required.
  Usage: program 1,2,3,4,5,6 2,2,4,4,9,9 3,3,5,5,7,7 [...]
  ```
- **Non-integers** or **wrong face count**:  
  ```
  ERROR: Dice “A,B,C” invalid—non-integer or incorrect face count.
  Example: 1,2,3,4,5,6
  ```

---

## 🔄 Game Flow

1. **Determine first move** (user vs. computer) via **fair generation** (0..1):
   - Computer generates secure random **x ∈ {0,1}** & **secret key** (≥256 bits).
   - Computes **HMAC_SHA3(key, x)** and displays HMAC.
   - User guesses **0** or **1**.
   - Computer reveals **x** and **key**; user verifies HMAC.

2. **Dice Selection:**
   - First mover chooses a dice.
   - Second mover chooses **any other** dice.
   - CLI menu:  
     ```
     0 – 2,2,4,4,9,9
     1 – 6,8,1,1,8,6
     X – exit
     ? – help
     ```

3. **Secure Roll for Each Player:**
   For each roll (computer and user):
   - Computer generates **r ∈ [0..faces−1]** & new **secret key**.
   - Computes **HMAC_SHA3(key, r)**; displays HMAC.
   - User inputs **y ∈ [0..faces−1]**.
   - Computer reveals **r** and **key**.
   - **Result index = (r + y) mod faces** → face value.

4. **Compare Rolls**: Higher face value wins.

---

## ❓ Help Option

Typing `?` displays a **probability table** (ASCII) of **win chances** for every dice pair:

```
Probability of User Win:
+---------------+-------------+-------------+-------------+
|               | Dice 0      | Dice 1      | Dice 2      |
+---------------+-------------+-------------+-------------+
| Dice 0        | – (0.3333)  | 0.4444      | 0.5556      |
| Dice 1        | 0.5556      | – (0.3333)  | 0.4444      |
| Dice 2        | 0.4444      | 0.5556      | – (0.3333)  |
+---------------+-------------+-------------+-------------+
```

- **Diagonals (–)** can show `–` or probability.
- **Widths adapt** to integer sizes.
- Use a **3rd-party ASCII table library**.

---

## ⚒ Architecture & Classes

Separate concerns into **6–9 classes**:

1. **DiceParser**: Parses CLI args → Dice objects  
2. **Dice**: Holds face values, provides utilities  
3. **ProbabilityCalculator**: Computes win probabilities  
4. **HelpTableRenderer**: Renders ASCII table  
5. **SecureRandom**: Generates cryptographically secure bytes/ints  
6. **HmacGenerator**: Calculates HMAC_SHA3(message, key)  
7. **FairRandomProtocol**: Encapsulates the HMAC-based protocol  
8. **GameController**: Orchestrates user/computer interaction  
9. **CliMenu**: Handles menus, input, validation  

Use **core** and **trusted libraries** (crypto APIs, HMAC, table printer).

---

## ⚙️ Running the Program

Example (Node.js):
```bash
node game.js 2,2,4,4,9,9 6,8,1,1,8,6 7,5,3,7,5,3
```

Sample session:
```
Let's determine who makes the first move.
Selected HMAC_SHA3(key, x) = C8E79615...
Guess x ∈ {0,1}:
0 – 0
1 – 1
? – help
Your selection: 0
Revealed x=1, KEY=BD9BE483... → verified.
Computer moves first and chooses dice [6,8,1,1,8,6].
Choose your dice:
...
```

---

## 📤 Submission Requirements

Email to `p.lebedev@itransition.com` with:

1. **Video (public link)** demonstrating:  
   - Launch with **4 identical dice** (`1,2,3,4,5,6` ×4).  
   - Launch with **3 intransitive dice** (`2,2,4,4,9,9 1,1,6,6,8,8 3,3,5,5,7,7`).  
   - **Invalid inputs** (too few dice, bad format).  
   - Displaying **help table**.  
   - **2 full game runs**.

2. **GitHub repo URL** with your source code.

> 🔈 No narration. Full-screen terminal. Code must be clearly legible.

---

## 🗓️ Deadline

**26 May 2025** (inclusive)  
No late submissions accepted.

---

## 🙋 FAQ

**Q: Can I skip HMAC and just show the number?**  
**A:** ❌ No—HMAC is essential for fairness proof.

**Q: May I use `%` operator for range?**  
**A:** ❌ No—use unbiased method (e.g., rejection sampling).

**Q: Can I use any table library?**  
**A:** ✅ Yes—as long as it’s open source and fits CLI.

---

## 🧠 Why This Task?

- Practice reading & implementing **complex specs**.  
- Learn about **HMAC** & **provable fairness**.  
- Solidify **OOP design** and **cryptography basics**.  
- Experience **non-transitive** probability modeling.

Good luck! 🚀
