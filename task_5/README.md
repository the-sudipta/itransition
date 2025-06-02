
# 📚 Task #5 — Bookstore Testing Web Application

> ⚡ **Objective:**  
> Build a dynamic web app that generates and displays **realistic fake book data** for testing a bookstore application.  
> Implement multi-language support, seed-based random data generation, infinite scrolling, and other dynamic features.

---

## 📝 Requirements

1. **Language & Region Selection**
    - Users can select at least 3 different languages/regions (e.g., English-USA, German-Germany, Japanese-Japan).
    - Titles, authors, and reviews must be generated to look realistic in the selected language/region.

2. **Seed Value Management**
    - Allow users to enter a seed value or generate a random one.
    - This ensures the same data is shown every time a user enters the same seed.

3. **Likes and Reviews per Book**
    - Users can set an average **likes** per book using a slider (0 to 10, fractional allowed).
    - Users can set an average **reviews** per book using a number field (fractional allowed).
    - For fractional values: e.g. 4.7 means 4 reviews always, and the 5th review with a 70% chance.

4. **Dynamic Table Display**
    - Display a table with columns:
        - Index, Random ISBN, Random Title, Random Author(s), Random Publisher
    - Data must update automatically if any parameter changes.
    - Infinite scrolling: load 20 records initially, then 10 more with each scroll.

5. **Expandable Book Details**
    - Each record is expandable to show:
        - A random book cover image with the title and author
        - Randomly generated reviews and authors

6. **Seeded Data Generation**
    - Use a seeded random generator (e.g., `seedrandom`) to generate data consistently.
    - Combine the user’s seed with the page number to generate unique, repeatable data.

---

## 🚀 Features

- Realistic, language-appropriate book data.
- Infinite scrolling with automatic loading of more records.
- Seed-based data consistency (same seed, same results every time).
- Likes and reviews logic that matches user specifications.
- Expandable book details with covers and reviews.

---

## 📦 Tech Stack

- **Frontend:** JavaScript (Vanilla or with a library like React).
- **Backend:** Node.js or PHP (optional, but recommended to generate data server-side).
- **Random Data:** Faker.js (or similar) with support for different languages/regions.
- **Seeded RNG:** seedrandom or similar.

---

## 📤 Submission Requirements

Email to the company

1. **Full Name**
2. **Link to the deployed app** (any suitable host).
3. **Link to the code repository**.
4. **Recorded video demonstration** showing:
    - Changing languages/regions
    - Infinite scrolling (5–10 pages)
    - Changing likes and reviews (e.g., from 0 to 0.5, then to 5)
    - Changing the seed and showing consistent data.

---

## 🗓️ Deadline

**08 June 2025** (inclusive)

---

## 🧠 Why This Task?

- Tests skills in **random data generation** and **seed management**.
- Builds experience in **dynamic UI design** (infinite scrolling).
- Practices **multilingual support** and **user-driven data changes**.

Good luck! 🚀
