class MeetingBlock {

    constructor() {
        this._doc = null;
        this._origin = { x: 0, y: 0 };
        this._data = {
            date: null,
            chairman: null,
            reader: null,
            topic: null,
            subtopic: null,
        };
    }

    /**
     * Provide jsPDF block in which the work should be done
     * @param doc
     * @returns {MeetingBlock}
     */
    in(doc) {
        this._doc = doc;
        return this;
    }

    /**
     * Set base position
     * @param {int} x
     * @param {int} y
     * @returns {MeetingBlock}
     */
    at(x, y) {
        this._origin.x = x;
        this._origin.y = y;
        return this;
    }

    /**
     * Provide data which should be rendered
     * @param {object} data
     * @param {string|null} [data.date]
     * @param {string|null} [data.chairman]
     * @param {string|null} [data.reader]
     * @param {string|null} [data.topic]
     * @param {string|null} [data.subtopic]
     * @returns {MeetingBlock}
     */
    withData(data = {}) {
        this._mergeIntoData(data);
        return this;
    }

    /**
     * Render the block in the document
     * @returns {MeetingBlock}
     */
    render() {
        if (! this._doc) throw new Error('Provide a jsPDF-Object in which the block should be rendered.');
        if (! this._data) throw new Error('Provide data for the block.');
        if (! this._data.date) throw new Error('Provide at minimum a date for the block.');

        this._renderDateColumn();
        this._renderTopic();
        this._renderChairman();
        if (this._data.subtopic) this._renderSubtopic();
        if (this._data.reader) this._renderReader();

        return this;
    }

    /**
     *
     * Renderer
     *
     */

    _renderDateColumn() {
        // Date
        this._doc
            .setFont('Inter', 'normal')
            .setFontSize(9)
            .setTextColor('#000000')
            .text(this._data.date, this._x(), this._y());
        // Vertical line
        this._doc
            .setLineWidth(0.35)
            .line(
                this._x(16), this._y(-5),
                this._x(16), this._y(7)
            );
    }

    _renderTopic() {
        this._doc
            .setFont('Inter', 'bold')
            .setFontSize(9)
            .setTextColor('#000000')
            .text(this._data.topic || '--', this._x(20), this._y());
    }

    _renderSubtopic() {
        this._doc
            .setFont('Inter', 'normal')
            .setFontSize(9)
            .setTextColor('#000000')
            .text(this._data.subtopic, this._x(20), this._y(4.6));
    }

    _renderChairman() {
        this._doc
            .setFont('Inter', 'normal')
            .setFontSize(9)
            .setTextColor('#000000')
            .text(this._data.chairman || '--', this._x(156), this._y());
    }

    _renderReader() {
        this._doc
            .setFont('Inter', 'normal')
            .setFontSize(9)
            .setTextColor('#000000')
            .text(this._data.reader, this._x(156), this._y(4.6));
    }

    /**
     *
     * Helpers
     *
     */
    _x(x = 0) {
        return this._origin.x + x;
    }

    _y(y = 0) {
        return this._origin.y + y;
    }

    _mergeIntoData(data = {}) {

        for (let prop in data) {
            this._data[prop] = data[prop];
        }

        return this._data;
    }
}

module.exports = MeetingBlock;
