<h1>Generate PowerPoint</h1>
<div class="p-3 mb-2 text-dark"
    style="background-color:rgb(248, 204, 60); border-radius: 5px; border: 2px solid rgb(210, 163, 10)">
    <span style="font-weight: bolder;">Instruction:</span><br>
    <ul>
        <li>Select book</li>
        <li>Select chapter</li>
        <li>Select verse</li>
        <li>Click the button add slide</li>
    </ul>
</div>

<div class="row">
    <div class="col-3">
        <select id="bookSelect" class="form-select mb-2"></select>
    </div>
    <div class="col-2">
        <select id="chapterSelect" class="form-select mb-2"></select>
    </div>
    <div class="col-2">
        <select id="verseSelect" class="form-select mb-2"></select>
    </div>
    <div class="col-5">
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

function createPpt() {
    if (selectedVerses.length === 0) {
        Swal.fire("No verses selected.", "", "error");
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