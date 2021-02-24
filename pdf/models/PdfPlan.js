const { jsPDF } = require("jspdf")
const fs = require("fs")
require('../fonts/Inter-normal');
require('../fonts/Inter-bold');

class PdfPlan {

    constructor(data) {
        this._doc = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'a4',
            putOnlyUsedFonts: true,
        });

        this._data = data;
        this._margin = { left: 20, top: 24, right: 20, bottom: 24 };

        this._content = {
            baseY: 45,
            rowIndex: -1, // because wie beginn with triggering _nextRow()-method
            rowHeight: 15
        };
    }

    render() {
        return this;
    }

    save(path) {
        // render headers
        for (let i = 1; i <= this._doc.getNumberOfPages(); i++) {
            this._doc.setPage(i);
            this._pageHeader();
        }

        return this._doc.save(path);
    }

    _imageData(image) {
        const imageData = fs.readFileSync(`./pdf/images/${image}`);
        return `data:image/png;base64,${imageData.toString('base64')}`
    }

    _currentRowY() {
        return this._content.baseY + (this._content.rowIndex * this._content.rowHeight);
    }

    _nextRow() {
        if (this._shouldAddPage()) {
            this._doc.addPage();
            this._content.rowIndex = 0;
            return;
        }

        this._content.rowIndex = this._content.rowIndex + 1;
    }

    _shouldAddPage() {
        return ((this._currentRowY() + this._content.rowHeight) >= (297 - this._margin.bottom));
    }

    _pageHeader() {}
 }

module.exports = PdfPlan;
