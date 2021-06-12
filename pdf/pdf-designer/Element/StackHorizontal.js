const Base = require("./Base");

class StackHorizontal extends Base {

    static create(elements = []) {
        return new StackHorizontal(elements || [])
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
        let currentX = x + this._space.left
        const contentY = y + this._space.top

        this._elements.forEach(element => {
            element.in(this._document).render(currentX, contentY)
            currentX += element.width
        })
    }

    removeChildren() {
        this._elements = []
    }

    set children(children) {
        this._elements = children;
    }

    get height() {
        if (this._contentHeight === 0) {
            this._elements.forEach(element => {
                element.in(this._document)
                this._contentHeight = element.height > this._contentHeight ? element.height : this._contentHeight
            })
        }

        return this._space.top + this._contentHeight + this._space.bottom
    }

    get width() {
        if (this._fixedWidth) return this._fixedWidth

        if (this._contentWidth === 0) {
            this._elements.forEach(element => {
                element.in(this._document)
                this._contentWidth += element.width
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

module.exports = StackHorizontal
