
# 📊 Task #6 — Collaborative Presentation Software

> ⚡ **Objective:**  
> Build a **real-time collaborative presentation software** where multiple users can create, view, and edit slides together, using an immediate and interactive web app interface.

---

## 📝 Requirements

1. **User Access**
    - Users can enter the application by providing an arbitrary nickname (no registration or authentication required).
    - Each user can create a new presentation or join an existing one.

2. **User Management**
    - The presentation creator can:
        - View the list of connected users (shown on the right).
        - Assign or revoke **Editor** status to any user.
    - Users with **Viewer** status can only view presentations without editing tools.

3. **Slide Management**
    - The presentation creator can:
        - Add new slides.
        - Delete existing slides.
    - Multiple editors can edit different slides simultaneously.
    - All changes are broadcasted live to all users (preferably using WebSockets).

4. **Persistent Storage**
    - All slide edits are stored permanently.
    - New users joining a presentation can see all existing slides and edits.

5. **Slide Editor**
    - Slide area fills the entire window (except for the top tool panel, left slide panel, and right users panel).
    - Supports editable, movable text blocks with markdown formatting.

6. **Present Mode**
    - Implement a **present mode** for presentations.

---

## 🚀 Features

- Real-time collaborative editing of slides.
- Persistent storage of presentation data.
- Nickname-based user access.
- Dynamic user roles (Editor/Viewer).
- Fully responsive and scalable layout.
- Markdown support for text blocks.
- Present mode for slideshows.

---

## 📦 Tech Stack

- **Frontend:** JavaScript (Vanilla or with React/Vue/Angular).
- **Backend:** Node.js with WebSocket support (e.g., Socket.io) or PHP with WebSockets.
- **Database:** PostgreSQL, MySQL, or MongoDB with unique index on nickname/email.
- **UI:** Any modern CSS framework from [Awesome CSS Frameworks](https://github.com/troxler/awesome-css-frameworks).
- **Markdown Support:** A suitable JavaScript markdown editor (e.g., EasyMDE, ToastUI).

---

## 📤 Submission Requirements

Email to the company:

1. **Full Name**
2. **Link to the deployed app** (any suitable host).
3. **Link to the code repository**.
4. **Recorded video demonstration** showing:
    - Creating and joining presentations.
    - Switching user roles (Editor/Viewer).
    - Live collaborative editing with multiple users.
    - Adding/removing slides.
    - Markdown text block editing.

---

## 🗓️ Deadline

**08 June 2025** (inclusive)

---

## 🧠 Why This Task?

- Tests skills in **real-time collaboration**.
- Demonstrates **WebSocket integration** and **dynamic UI design**.
- Practices **user management** and **persistent data storage**.

Good luck! 🚀
