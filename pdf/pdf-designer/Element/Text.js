const Base = require("./Base");

const INTER_CONVERSION_FACTOR = 0.26;

class Text extends Base {

    static create(text) {
        if (! text && text !== '') throw new Error('Provide the text for a Text Element.')

        return new Text(text)
    }

    constructor(text) {
        super()

        this._text = text
        this._fontFamily = 'Inter'
        this._fontStyle = 'normal'
        this._fontSize = 9
        this._leadingFactor = 1.5
        this._color = '#000000'
        this._maxWidth = 0
    }

    render(x, y) {
        if (! this._document) throw new Error('Provide a jsPDF-Object in which the Text Element should be rendered with the "in"-method.')

        this._prepareRendering()

        // draw background
        if (this._backgroundColor) {
            this._document.setFillColor(this._backgroundColor).rect(
                x, y, this.width, this.height, 'F'
            )
        }

        // draw text
        this._document.text(this._text,
            x + this._space.left,
            y + this._leadingHeight + this._space.top,
            { maxWidth: this._maxWidth }
        )
    }

    // Getters

    get height() {
        let lines = 1

        if (this._maxWidth > 0) {
            const stringWidth = this._document.getStringUnitWidth(this._text) * this._fontSize * 0.3527777778
            lines = Math.ceil(  stringWidth / this._maxWidth)
        }

        return this._space.top + (lines * this._leadingHeight) + this._space.bottom
    }

    get width() {
        if (this._fixedWidth) return this._fixedWidth
        if (this._maxWidth) return this._maxWidth

        if (! this._document) throw new Error('Provide a jsPDF-Object in which the Text Element should be rendered with the "in"-method.')

        this._prepareRendering()
        const textWidth = this._document.getStringUnitWidth(this._text) * this._fontSize * 0.3527777778

        return this._space.left + textWidth + this._space.right
    }

    get _leadingHeight() {
        return this._fontSize * INTER_CONVERSION_FACTOR * this._leadingFactor
    }

    // Setters

    maxWidth(maxWidth) {
        this._maxWidth = maxWidth
        return this
    }

    fontSize(size) {
        this._fontSize = size
        return this
    }

    leading(factor) {
        this._leadingFactor = factor
        return this
    }

    color(color) {
        this._color = color
        return this
    }

    bold() {
        this._fontStyle = 'bold'
        return this
    }

    normal() {
        this._fontStyle = 'normal'
        return this
    }

    // Helpers
    _prepareRendering() {
        this._document
            .setLineHeightFactor(this._leadingFactor)
            .setFont(this._fontFamily, this._fontStyle)
            .setFontSize(this._fontSize)
            .setTextColor(this._color)
    }
}

module.exports = Text
