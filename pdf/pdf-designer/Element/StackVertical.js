const Base = require("./Base");

class StackVertical extends Base {

    static create(elements = []) {
        return new StackVertical(elements || [])
    }

    constructor(elements = []) {
        super()

        this._elements = elements
        this._contentHeight = 0
        this._contentWidth = 0
    }

    render(x, y) {
        if (! this._document) throw new Error('Provide a jsPDF-Object in which the Text Element should be rendered with the "in"-method.')

        // draw background
        if (this._backgroundColor) {
            this._document.setFillColor(this._backgroundColor).rect(
                x, y, this.width, this.height, 'F'
            )
        }

        // draw elements
        const currentX = x + this._space.left
        let currentY = y + this._space.top
        this._elements.forEach(element => {
            element.in(this._document).render(currentX, currentY)
            currentY += element.height
        })
    }

    set children(children) {
        this._elements = children;
    }

    removeChildren() {
        this._elements = []
        this._contentWidth = 0
        this._contentHeight = 0
    }

    get height() {
        if (this._contentHeight === 0) {
            this._elements.forEach(element => {
                element.in(this._document)
                this._contentHeight += element.height
            })
        }

        return this._space.top + this._contentHeight + this._space.bottom
    }

    get width() {
        if (this._fixedWidth) return this._fixedWidth

        if (this._contentWidth === 0) {
            this._elements.forEach(element => {
                element.in(this._document)
                this._contentWidth = element.width > this._contentWidth ? element.width : this._contentWidth
            })
        }

        return this._space.left + this._contentWidth + this._space.right
    }

    get hasChildren() {
        return this._elements.length > 0
    }

    get children() {
        return this._elements
    }

    // Setters

    append(element) {
        this._elements.push(element)
        return this
    }
}

module.exports = StackVertical
