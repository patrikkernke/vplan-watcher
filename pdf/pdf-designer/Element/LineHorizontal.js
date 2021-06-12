const Base = require("./Base");

class LineHorizontal extends Base {

    static create(length = null, thickness = null) {
        return new LineHorizontal(length, thickness)
    }

    constructor(length, thickness) {
        super()

        this._thickness = thickness || 0.5
        this._color = '#000000'
        this._length = length || 10
        this._lineStyle = 'S'
    }

    render(x, y) {
        if (! this._document) throw new Error('Provide a jsPDF-Object in which the Text Element should be rendered with the "in"-method.')

        // draw line
        let startX = x + this._space.left
        const startY = y + this._space.top

        this._document
            .setDrawColor(this._color)
            .setLineWidth(this._thickness)
            .line(startX, startY, startX  + this._length, startY)
    }

    get height() {
        return this._space.top + this._thickness + this._space.bottom
    }

    get width() {
        if (this._fixedWidth) return this._fixedWidth

        return this._space.left + this._length + this._space.right
    }

    // Setters

    thickness(thickness) {
        this._thickness = thickness
        return this
    }

    color(color) {
        this._color = color
        return this
    }

    length(length) {
        this._length = length
        return this
    }

    solid() {
        this._lineStyle = 'S'
        return this
    }

    dashed(length, gap) {
        this._lineStyle = 'S'
        return this
    }
}

module.exports = LineHorizontal
