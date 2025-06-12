<h1>Select Verses to Generate PowerPoint</h1>

<select id="bookSelect"></select>
<select id="chapterSelect"></select>
<select id="verseSelect"></select>
<button onclick="addVerse()">Add to List</button>

<h3>Selected Verses:</h3>
<ul id="verseList"></ul>

<button onclick="createPpt()">Create PPT</button>

<script>
let bibleData = {};
let selectedVerses = [];

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
    for (let book in bibleData) {
        bookSelect.innerHTML += `<option value="${book}">${book}</option>`;
    }

    bookSelect.addEventListener("change", () => {
        populateChapters(bookSelect.value);
    });
}

function populateChapters(book) {
    const chapterSelect = document.getElementById("chapterSelect");
    chapterSelect.innerHTML = `<option value="">Select Chapter</option>`;
    const chapters = bibleData[book];
    for (let chapter in chapters) {
        chapterSelect.innerHTML += `<option value="${chapter}">${chapter}</option>`;
    }

    chapterSelect.addEventListener("change", () => {
        populateVerses(book, chapterSelect.value);
    });
}

function populateVerses(book, chapter) {
    const verseSelect = document.getElementById("verseSelect");
    verseSelect.innerHTML = `<option value="">Select Verse</option>`;
    const verses = bibleData[book][chapter];
    for (let verse in verses) {
        verseSelect.innerHTML += `<option value="${verse}">${verse}</option>`;
    }
}

function addVerse() {
    const book = document.getElementById("bookSelect").value;
    const chapter = document.getElementById("chapterSelect").value;
    const verse = document.getElementById("verseSelect").value;

    if (!book || !chapter || !verse) {
        alert("Please select book, chapter, and verse.");
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
    li.textContent = `${reference} - ${text}`;
    li.style.marginBottom = "5px";

    const removeBtn = document.createElement("button");
    removeBtn.textContent = "❌ Remove";
    removeBtn.style.marginLeft = "10px";
    removeBtn.onclick = () => {
        selectedVerses = selectedVerses.filter(v => v.ref !== reference);
        verseList.removeChild(li);
    };

    li.appendChild(removeBtn);
    verseList.appendChild(li);
}

function createPpt() {
    if (selectedVerses.length === 0) {
        alert("No verses selected.");
        return;
    }

    let pptx = new PptxGenJS();
    selectedVerses.forEach(v => {
        let slide = pptx.addSlide();
        slide.addText([{
                text: v.ref + "\n",
                options: {
                    fontSize: 30,
                    bold: true,
                    color: '003366'
                }
            },
            {
                text: v.text,
                options: {
                    fontSize: 36,
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
</script>