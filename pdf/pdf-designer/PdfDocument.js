const { jsPDF } = require('jspdf')
const Pages = require("./Element/Pages");

// embed fonts in jsPDF
require('../fonts/Inter-normal');
require('../fonts/Inter-bold');

class PdfDocument  {

    constructor(data) {
        this._document = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'a4',
            putOnlyUsedFonts: true,
        });

        this._data = data
        this._margin = [0, 15]
    }

    // to be filled by the child class
    header(data, pages) { return null }
    content(data, pages) { return null }
    footer(data, pages) { return null }

    render() {
        const pages = Pages.create().in(this._document)

        const header = this.header(this._data, pages)
        if (header) pages.appendToHeader(header)

        const content = this.content(this._data, pages)
        if (content) pages.append(content).space(...this._margin)

        const footer = this.footer(this._data, pages)
        if (footer) pages.appendToFooter(footer)

        pages.render()

        return this;
    }

    save(path) {
        return this._document.save(path);
    }

    get currentPageNumber() {
        return this._document.getCurrentPageInfo().pageNumber
    }

    get contentWidth() {
        return 210 - this.margin.left - this.margin.right
    }

    get margin() {
        let margin = {}

        switch (this._margin.length) {
            case 0:
                margin = {top: 0, right: 0, bottom: 0, left: 0}
                break
            case 1:
                margin = {
                    top: this._margin[0],
                    right: this._margin[0],
                    bottom: this._margin[0],
                    left: this._margin[0]
                }
                break
            case 2:
                margin = {
                    top: this._margin[0],
                    right: this._margin[1],
                    bottom: this._margin[0],
                    left: this._margin[1]
                }
                break
            case 3:
                margin = {
                    top: this._margin[0],
                    right: this._margin[1],
                    bottom: this._margin[2],
                    left: this._margin[1]
                }
                break
            case 4:
                margin = {
                    top: this._margin[0],
                    right: this._margin[1],
                    bottom: this._margin[2],
                    left: this._margin[3]
                }
                break
            default:
                margin = {top: 0, right: 0, bottom: 0, left: 0}
                break
        }

        return margin
    }
}

module.exports = PdfDocument
