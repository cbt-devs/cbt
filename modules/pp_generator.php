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

<div class="btn-group" role="group" aria-label="Capsule buttons" id="capsuleToggle">
    <button type="button" class="btn btn-primary px-4 rounded-start-pill active" data-type="bible">
        <i class="fa-solid fa-book-open"></i> Bible
    </button>
    <button type="button" class="btn btn-outline-primary px-4 rounded-end-pill" data-type="hymnals">
        <i class="fa-solid fa-music"></i> Hymnals
    </button>
</div>

<div id="bibleSection">
    <div class="row g-2 align-items-end mt-3">
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
                <button type="button" class="btn btn-primary" onclick="addVerse()">
                    <i class="fa-solid fa-plus"></i> Add Slide
                </button>
            </div>
        </div>
    </div>

    <h4>Slides Contents:</h4>
    <ul id="verseList"></ul>

    <button class="btn btn-primary" onclick="createPpt()">
        <i class="fa-solid fa-file-powerpoint"></i> Create PowerPoint
    </button>
</div>

<div id="hymnalSection" class="mt-3" style="display: none;">
    <div class="row g-2 align-items-end">
        <div class="col-md-6">
            <select id="hymnSelect"></select>
            <div id="hymnLyrics" class="mt-5"></div>
        </div>
    </div>

    <button class="btn btn-primary mt-3" onclick="createHymnPpt()">
        <i class="fa-solid fa-file-powerpoint"></i> Create PowerPoint
    </button>
</div>

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

    // Fetch Bible data and populate the Book select
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

    // Populate Book Select
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

    // Add selected verse to the list
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

        Swal.fire({
            title: 'Enter file name',
            input: 'text',
            inputLabel: 'PowerPoint filename',
            inputValue: 'Lesson - ',
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'You must enter a filename!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const filename = result.value.trim() + ".pptx";

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

                pptx.writeFile(filename);
            }
        });
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

    // Init on DOM ready
    document.addEventListener("DOMContentLoaded", function() {
        // Bind NiceSelect placeholders
        ["bookSelect", "chapterSelect", "verseSelect"].forEach(id => {
            refreshNiceSelect(document.getElementById(id));
        });
    });
})();

(function() {
    let hymnsData = {};

    // Load hymns JSON
    fetch("assets/hymns.json")
        .then(response => response.json())
        .then(data => {
            hymnsData = data; // store the full JSON object, not just hymns
            populateHymnSelect();
        });

    window.createHymnPpt = function() {
        const hymnSelect = document.getElementById("hymnSelect");
        const hymnNumber = hymnSelect.value;

        if (!hymnNumber) {
            Swal.fire("No hymn selected.", "", "error");
            return;
        }

        const hymn = hymnsData.hymns[hymnNumber];
        if (!hymn) {
            Swal.fire("Invalid hymn selected.", "", "error");
            return;
        }

        Swal.fire({
            title: 'Enter file name',
            input: 'text',
            inputLabel: 'PowerPoint filename',
            inputValue: hymn.titleWithHymnNumber,
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'You must enter a filename!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const filename = result.value.trim() + ".pptx";

                let pptx = new PptxGenJS();

                // Add each verse as a separate slide
                hymn.verses.forEach((verseText, index) => {
                    let slide = pptx.addSlide();
                    slide.addImage({
                        path: "assets/img/background.jpg",
                        x: 0,
                        y: 0,
                        w: "100%",
                        h: "100%"
                    });
                    slide.addText([{
                            text: `${hymn.titleWithHymnNumber} - Verse ${index + 1}\n`,
                            options: {
                                fontSize: 24,
                                bold: true,
                                color: '003366'
                            }
                        },
                        {
                            text: verseText,
                            options: {
                                fontSize: 28,
                                color: '000000'
                            }
                        }
                    ], {
                        x: 0.5,
                        y: 1,
                        w: '90%',
                        h: 4
                    });
                });

                // Add chorus (optional)
                if (hymn.chorus) {
                    let slide = pptx.addSlide();
                    slide.addImage({
                        path: "assets/img/background.jpg",
                        x: 0,
                        y: 0,
                        w: "100%",
                        h: "100%"
                    });
                    slide.addText([{
                            text: `${hymn.titleWithHymnNumber} - Chorus\n`,
                            options: {
                                fontSize: 24,
                                bold: true,
                                color: '003366'
                            }
                        },
                        {
                            text: hymn.chorus,
                            options: {
                                fontSize: 28,
                                color: '000000'
                            }
                        }
                    ], {
                        x: 0.5,
                        y: 1,
                        w: '90%',
                        h: 4
                    });
                }

                pptx.writeFile(filename);
            }
        });
    }

    // Populate hymnal <select>
    function populateHymnSelect() {
        const hymnSelect = document.getElementById("hymnSelect");
        hymnSelect.innerHTML = `<option value="">Select Hymn</option>`;

        const categories = hymnsData.categories;
        const hymns = hymnsData.hymns;

        Object.entries(categories).forEach(([category, hymnIds]) => {
            const optgroup = document.createElement("optgroup");
            optgroup.label = category.charAt(0).toUpperCase() + category.slice(1);

            hymnIds.forEach(id => {
                const hymn = hymns[id];
                if (hymn) {
                    const option = document.createElement("option");
                    option.value = hymn.number;
                    option.textContent = hymn.titleWithHymnNumber;
                    optgroup.appendChild(option);
                }
            });

            hymnSelect.appendChild(optgroup);
        });

        refreshNiceSelect(hymnSelect);

        hymnSelect.addEventListener("change", () => {
            displayHymnLyrics(hymnSelect.value);
        });
    }


    // Display selected hymn lyrics
    function displayHymnLyrics(hymnNumber) {
        const hymn = hymnsData.hymns[hymnNumber];
        const container = document.getElementById("hymnLyrics");

        if (!hymn) {
            container.innerHTML = "";
            return;
        }

        let html = `<h5>${hymn.titleWithHymnNumber}</h5><hr>`;

        // Verses
        hymn.verses.forEach((v, i) => {
            html += `<p><strong>Verse ${i + 1}:</strong><br>${v.replace(/\n/g, "<br>")}</p>`;
        });

        // Chorus (optional)
        if (hymn.chorus) {
            html += `<p><strong>Chorus:</strong><br>${hymn.chorus.replace(/\n/g, "<br>")}</p>`;
        }

        container.innerHTML = html;
    }

    // Rebind NiceSelect (if used)
    function refreshNiceSelect(selectElement) {
        const wrapper = selectElement.nextElementSibling;
        if (wrapper && wrapper.classList.contains("nice-select")) {
            wrapper.remove();
        }
        NiceSelect.bind(selectElement, {
            searchable: true
        });
    }
})();


$(document).ready(function() {
    const tooltipEl = document.querySelector('[data-bs-toggle="tooltip"]');

    if (tooltipEl) {
        new bootstrap.Tooltip(tooltipEl);
    } else {
        console.log('Tooltip element not found.');
    }

    const group = document.getElementById('capsuleToggle');
    const buttons = group.querySelectorAll('button');

    const bibleSection = document.getElementById("bibleSection");
    const hymnalSection = document.getElementById("hymnalSection");

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Style toggling
            buttons.forEach(b => {
                b.classList.remove('btn-primary', 'btn-outline-primary', 'active');
                b.classList.add('btn-outline-primary');
            });
            btn.classList.remove('btn-outline-primary');
            btn.classList.add('btn-primary', 'active');

            // Section toggling
            const selected = btn.dataset.type;
            console.log('Selected:', selected);

            if (selected === 'bible') {
                bibleSection.style.display = 'block';
                hymnalSection.style.display = 'none';
            } else if (selected === 'hymnals') {
                bibleSection.style.display = 'none';
                hymnalSection.style.display = 'block';
            }
        });
    });
});
</script>