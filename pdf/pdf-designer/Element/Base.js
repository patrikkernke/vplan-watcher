class Base {

    constructor() {
        this._document = null
        this._backgroundColor = null
        this._fixedWidth = null
        this._space = { top: 0, right: 0, bottom: 0, left: 0 }
    }

    in(document) {
        this._document = document
        return this
    }

    // to be filled by child class
    render(x, y) { }
    removeChildren() { }
    set children(children) { }

    // Getters

    // to be filled by child class
    get height() { return 0 }
    get width() { return 0 }
    get children() { return null }
    get hasChildren() { return false }
    get hasNotChildren() { return ! this.hasChildren }

    // Setters
    color(color) {
        this._color = color
        return this
    }

    backgroundColor(color) {
        this._backgroundColor = color
        return this
    }

    space(top, right = null, bottom = null, left = null) {
        this._space = {
            top: top,
            right: right !== null ? right : top,
            bottom: bottom !== null ? bottom : top,
            left: left !== null ? left : right !== null ? right : top
        }

        return this
    }

    top(top) {
        this._space.top = top
        return this
    }

    bottom(bottom) {
        this._space.bottom = bottom
        return this
    }

    left(left) {
        this._space.left = left
        return this
    }

    right(right) {
        this._space.right = right
        return this
    }

    fixedWidth(width) {
        this._fixedWidth = width

        return this
    }
}

module.exports = Base
