
    document.addEventListener("DOMContentLoaded", function () {
        // --- Current day (JS override for client-side accuracy) ---
        const currentDayEl = document.getElementById("current-day");
        if (currentDayEl) {
            const now = new Date();
            const options = {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
            };
            currentDayEl.textContent = now.toLocaleDateString(undefined, options);
        }

        // --- Quote of the Day ---
        const quotes = [
            {
                text: "Take vacations. You can make more money, not more time.",
                author: "Unknown",
            },
            {
                text: "Rest is not a waste of time; it’s how you become sharper for what’s next.",
                author: "Unknown",
            },
            {
                text: "Balance is not something you find, it’s something you create.",
                author: "Jana Kingsford",
            },
            {
                text: "You don’t have to burn out to prove you’re working hard.",
                author: "Unknown",
            },
            {
                text: "Work hard, rest harder. Your future self will thank you.",
                author: "Unknown",
            },
            {
                text: "Remote work is freedom. Use it to build the life you want, not to work all the time.",
                author: "Unknown",
            },
            {
                text: "Sometimes the most productive thing you can do is relax.",
                author: "Mark Black",
            },
        ];

        const quoteTextEl = document.getElementById("quote-text");
        const quoteAuthorEl = document.getElementById("quote-author");

        if (quoteTextEl && quoteAuthorEl) {
            // Simple deterministic “quote of the day”
            const today = new Date();
            const dayOfYear = Math.floor(
                (today - new Date(today.getFullYear(), 0, 0)) / 86400000
            );
            const index = dayOfYear % quotes.length;
            const chosen = quotes[index];

            quoteTextEl.textContent = "“" + chosen.text + "”";
            quoteAuthorEl.textContent = "— " + chosen.author;
        }


         async function getPendingStatus() {
         try {
        const res = await fetch('/pending-leaves');
        

        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);

        const data = await res.json();
        

        const pendingEl = document.getElementById('pendingleaves');
        if (pendingEl) {
            pendingEl.textContent = data.pending;
        }

    } catch (err) {
        console.error('Error fetching pending leaves', err);
    }
}


getPendingStatus();



    });

           