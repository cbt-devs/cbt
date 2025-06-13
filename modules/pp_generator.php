<style>
ol li {
    text-align: left;
}

.nice-select {
    width: 100%;
    margin-bottom: 0 !important;
}
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="m-0">Generate PowerPoint</h1>

    <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom"
        data-bs-html="true" title="<div style='text-align: left; font-size: 0.9rem; padding-left: 10px;'>
            <strong>Steps:</strong>
            <ol style='padding-left: 1.2em; margin-bottom: 0;'>
                <li>Select book</li>
                <li>Select chapter</li>
                <li>Select verse</li>
                <li>Click Add slide</li>
                <li>Once done adding the slide click Create Powerpoint</li>
            </ol>
        </div>">
        Instruction Manual <i class="fa-solid fa-book"></i>
    </button>
</div>

<div class="row g-2 align-items-end">
    <div class="col-md-3">
        <select id="bookSelect">
            <option value="">Select Book</option>
        </select>
    </div>
    <div class="col-md-3">
        <select id="chapterSelect">
            <option value="">Select Chapter</option>
        </select>
    </div>
    <div class="col-md-3">
        <select id="verseSelect">
            <option value="">Select Verse</option>
        </select>
    </div>
    <div class="col-md-3 d-grid">
        <button type="button" class="btn btn-success" onclick="addVerse()">
            <i class="fa-solid fa-plus"></i> Add Slide
        </button>
    </div>
</div>

<h4>Slides Contents:</h4>
<ul id="verseList"></ul>

<button class="btn btn-success" onclick="createPpt()">
    <i class="fa-solid fa-file-powerpoint"></i> Create PowerPoint
</button>

<script>
(function() {
    let bibleData = {};
    let selectedVerses = [];

    // Utility: Rebind NiceSelect on a given select element
    function refreshNiceSelect(selectElement) {
        const existing = selectElement.closest(".nice-select");
        if (existing) existing.remove();
        NiceSelect.bind(selectElement, {
            searchable: true
        });
    }

    fetch("assets/kjvbible.txt")
        .then(response => response.text())
        .then(text => {
            const booksJson = text.split(/(?=\{"book":)/).map(obj => JSON.parse(obj));
            booksJson.forEach(book => {
                const bookName = book.book;
                bibleData[bookName] = {};
                book.chapters.forEach(chap => {
                    const chapNum = chap.chapter;
                    bibleData[bookName][chapNum] = {};
                    chap.verses.forEach(v => {
                        bibleData[bookName][chapNum][v.verse] = v.text;
                    });
                });
            });
            populateBooks();
        });

    function populateBooks() {
        const bookSelect = document.getElementById("bookSelect");
        bookSelect.innerHTML = `<option value="">Select Book</option>`;
        const books = Object.keys(bibleData);

        books.forEach(book => {
            bookSelect.innerHTML += `<option value="${book}">${book}</option>`;
        });

        refreshNiceSelect(bookSelect);

        if (books.length > 0) {
            bookSelect.value = books[0];
            populateChapters(books[0]); // Auto-select first book
        }

        bookSelect.addEventListener("change", () => {
            populateChapters(bookSelect.value);
        });
    }

    function populateChapters(book) {
        const chapterSelect = document.getElementById("chapterSelect");
        chapterSelect.innerHTML = `<option value="">Select Chapter</option>`;
        const chapters = Object.keys(bibleData[book]);

        chapters.forEach(chapter => {
            chapterSelect.innerHTML += `<option value="${chapter}">${chapter}</option>`;
        });

        refreshNiceSelect(chapterSelect);

        if (chapters.length > 0) {
            chapterSelect.value = chapters[0];
            populateVerses(book, chapters[0]); // Auto-select first chapter
        }

        chapterSelect.addEventListener("change", () => {
            populateVerses(book, chapterSelect.value);
        });
    }

    function populateVerses(book, chapter) {
        const verseSelect = document.getElementById("verseSelect");
        verseSelect.innerHTML = `<option value="">Select Verse</option>`;
        const verses = Object.keys(bibleData[book][chapter]);

        verses.forEach(verse => {
            verseSelect.innerHTML += `<option value="${verse}">${verse}</option>`;
        });

        refreshNiceSelect(verseSelect);

        if (verses.length > 0) {
            verseSelect.value = verses[0];
            // Optional: You can trigger verse display or log here
        }
    }

    window.addVerse = function() {
        const book = document.getElementById("bookSelect").value;
        const chapter = document.getElementById("chapterSelect").value;
        const verse = document.getElementById("verseSelect").value;

        if (!book || !chapter || !verse) {
            Swal.fire("Please select book, chapter, and verse.", "", "error");
            return;
        }

        const reference = `${book} ${chapter}:${verse}`;
        const text = bibleData[book][chapter][verse];

        if (selectedVerses.some(v => v.ref === reference)) return;

        const verseObj = {
            ref: reference,
            text
        };
        selectedVerses.push(verseObj);

        const verseList = document.getElementById("verseList");
        const li = document.createElement("li");

        const removeBtn = document.createElement("button");
        removeBtn.innerHTML = '<i class="fa-solid fa-trash"></i> Remove';
        removeBtn.className = "btn btn-danger btn-sm me-2 mt-2 mb-2";
        removeBtn.onclick = () => {
            selectedVerses = selectedVerses.filter(v => v.ref !== reference);
            verseList.removeChild(li);
        };

        const verseText = document.createElement("span");
        verseText.textContent = `${reference} - ${text}`;

        li.appendChild(removeBtn);
        li.appendChild(verseText);
        verseList.appendChild(li);
    }

    // Create the PowerPoint file
    window.createPpt = function() {
        if (selectedVerses.length === 0) {
            Swal.fire("No verses selected.", "", "error");
            return;
        }

        let pptx = new PptxGenJS();
        selectedVerses.forEach(v => {
            let slide = pptx.addSlide();
            slide.addImage({
                path: "assets/img/background.jpg",
                x: 0,
                y: 0,
                w: "100%",
                h: "100%"
            });
            slide.addText([{
                    text: v.ref + "\n",
                    options: {
                        fontSize: 25,
                        bold: true,
                        color: '003366'
                    }
                },
                {
                    text: v.text,
                    options: {
                        fontSize: 30,
                        color: '000000'
                    }
                }
            ], {
                x: 0.5,
                y: 1,
                w: '90%',
                h: 3
            });
        });

        pptx.writeFile("Selected_Verses.pptx");
    }

    function refreshNiceSelect(selectElement) {
        // Remove any old NiceSelect wrappers
        const wrapper = selectElement.nextElementSibling;
        if (wrapper && wrapper.classList.contains("nice-select")) {
            wrapper.remove();
        }

        // Ensure it's visible and bound again
        selectElement.style.display = "";
        NiceSelect.bind(selectElement, {
            searchable: true
        });
    }
})();

$(document).ready(function() {
    ["bookSelect", "chapterSelect", "verseSelect"].forEach(id => {
        refreshNiceSelect(document.getElementById(id));
    });

    const tooltipEl = document.querySelector('[data-bs-toggle="tooltip"]');
    console.log('Tooltip Element:', tooltipEl); // Check if element is found

    if (tooltipEl) {
        const tooltip = new bootstrap.Tooltip(tooltipEl);
        console.log('Tooltip initialized:', tooltip);
    } else {
        console.log('Tooltip element not found.');
    }
});
</script>