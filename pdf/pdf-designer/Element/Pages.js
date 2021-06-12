const Base = require("./Base")

class Pages extends Base {

    static create(elements, header, footer) {
        return new Pages(
            elements || [],
            header || [],
            footer || [],
        )
    }

    constructor(elements = [], header = [], footer = []) {
        super()

        this._headerElements = header
        this._elements = elements
        this._footerElements = footer
    }

    render() {
        if (! this._document) throw new Error('Provide a jsPDF-Object in which the Text Element should be rendered with the "in"-method.')

        this._renderPageLayout()
        this._renderContent()
        this._document.putTotalPages('{n}')

        return this
    }

    // Getters

    get pageWidth() {
        return this._document.getPageWidth();
    }

    get pageHeight() {
        return this._document.getPageHeight();
    }

    get _headerHeight() {
        return this._heightFor('_headerElements')
    }

    get _contentHeight() {
        return this._heightFor('_elements')
    }

    get _footerHeight() {
        return this._heightFor('_footerElements')
    }

    get _initialContentX() {
        return this._space.left
    }

    get _initialContentY() {
        return this._headerHeight + this._space.top
    }

    get _initialContentHeight() {
        return this._space.top + this._space.bottom
    }

    get _initialPageX() {
        return this._space.left
    }

    get _maxContentHeight() {
        return this._document.getPageHeight() - this._headerHeight - this._footerHeight - this._space.top - this._space.bottom
    }

    // Setters

    _appendTo(property, elements) {
        if (! this.hasOwnProperty(property)) throw new Error(`Property "${property}" does not exist in the Pages Object.`)

        if (! Array.isArray(elements)) {
            this[property].push(elements)
            return
        }

        elements.forEach(element => this[property].push(element))
    }

    append(elements) {
        this._appendTo('_elements', elements)
        return this
    }

    appendToHeader(elements) {
        this._appendTo('_headerElements', elements)
        return this
    }

    appendToFooter(elements) {
        this._appendTo('_footerElements', elements)
        return this
    }

    _addPage() {
        this._document.addPage()
        this._renderPageLayout()
    }

    // Helpers

    _renderContent() {
        let renderedContentHeight = 0
        let currentX = this._initialContentX
        let currentY = this._initialContentY

        this._elements.forEach((element, index) => {
            element.in(this._document)
            if (! this._willElementFitOnCurrentPage(renderedContentHeight, element.height) && index > 0) {
                this._addPage()
                renderedContentHeight = 0
                currentX = this._initialContentX
                currentY = this._initialContentY
            }

            element.render(currentX, currentY)
            renderedContentHeight += element.height
            currentY += element.height
        })
    }

    _renderPageLayout() {
        // draw page background
        if (this._backgroundColor) {
            this._document.setFillColor(this._backgroundColor).rect(
                0, 0, this._document.getPageWidth(), this._document.getPageHeight(), 'F'
            )
        }

        this._renderSectionFor('_headerElements', 0, 0)
        this._renderSectionFor('_footerElements', 0, this._document.getPageHeight() - this._footerHeight)
    }

    _renderSectionFor(property, x, y) {
        if (! this.hasOwnProperty(property)) throw new Error(`Property "${property}" does not exist in the Pages Object.`)

        let currentX = x
        let currentY = y

        this[property].forEach(element => {
            element.in(this._document).render(currentX, currentY)
            currentY += element.height
        })
    }

    _heightFor(property) {
        if (! this.hasOwnProperty(property)) throw new Error(`Property "${property}" does not exist in the Pages Object.`)

        let height = 0

        this[property].forEach(element => {
            height += element.height
        })

        return height
    }

    _willElementFitOnCurrentPage(contentHeight, elementHeight) {
        return contentHeight + elementHeight <= this._maxContentHeight
    }
}

module.exports = Pages
