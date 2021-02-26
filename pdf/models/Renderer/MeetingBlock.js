const fs = require("fs")

class MeetingBlock
{
    constructor()
    {
        this._doc = null;
        this._origin = { x: 0, y: 0 };
        this._data = {
            date: null,
            chairman: null,
            reader: null,
            topic: null,
            subtopic: null,
            label: null,
        };

        this._color = {
            primary: '#000000',
            base: '#000000'
        }

        this._safeSpace = 2;
        this._lineSpace = 1.5;
    }

    /**
     * Provide jsPDF block in which the work should be done
     * @param doc
     * @returns {MeetingBlock}
     */
    addTo(doc)
    {
        this._doc = doc;
        return this;
    }

    /**
     * Set base position
     * @param {int} x
     * @param {int} y
     * @returns {MeetingBlock}
     */
    at(x, y)
    {
        this._origin.x = x;
        this._origin.y = y;
        return this;
    }

    /**
     * Set primary color for theming the block
     * @param {string} hexColor
     */
    withColorStyle(hexColor)
    {
        this._color.primary = hexColor;
    }

    /**
     * Provide data which should be rendered
     * @param {object} data
     * @param {string|null} [data.date]
     * @param {string|null} [data.chairman]
     * @param {string|null} [data.reader]
     * @param {string|null} [data.topic]
     * @param {string|null} [data.subtopic]
     * @param {string|null} [data.label]
     * @returns {MeetingBlock}
     */
    withData(data = {})
    {
        this._mergeIntoData(data);
        return this;
    }

    /**
     * Render the block in the document
     * @returns {MeetingBlock}
     */
    render()
    {
        if (! this._doc) throw new Error('Provide a jsPDF-Object in which the block should be rendered.');
        if (! this._data) throw new Error('Provide data for the block.');
        if (! this._data.date) throw new Error('Provide at minimum a date for the block.');

        // this._renderBackground();

        const firstLineY = this._safeSpace + this._textHeight();
        const secondLineY = firstLineY + this._textHeight() + this._lineSpace;
        const thirdLineY = secondLineY + this._textHeight() + this._lineSpace;

        this._renderDate(0, firstLineY);
        this._renderVerticalLine(16);

        if (this._data.label) this._renderLabel(16, 0);
        this._renderTopic(20, this._data.label ? secondLineY : firstLineY);
        if (this._data.subtopic) this._renderSubtopic(20, this._data.label ? thirdLineY : secondLineY);

        if (this._data.chairman) this._renderChairman(150, firstLineY);
        if (this._data.reader) this._renderReader(150, secondLineY);

        return this;
    }

    get height()
    {
        let countLines = 0;
        if (this._data.label) countLines += 1;
        if (this._data.date || this._data.topic) countLines += 1;
        if (this._data.subtopic || this._data.reader) countLines += 1;

        const sumTextHeight = countLines * this._textHeight();
        const sumLineSpace = (countLines - 1) * this._lineSpace;

        return sumTextHeight + sumLineSpace + this._safeSpace*2 ;
    }

    /**
     *
     * Renderer
     *
     */

    _renderBackground()
    {
        this._doc.setFillColor('#EEEEEE').rect(
            this._origin.x,
            this._origin.y,
            170,
            this.height,
            'F'
        );
    }

    _renderDate(x, y)
    {
        this._doc
            .setFont('Inter', 'normal')
            .setFontSize(9)
            .setTextColor(this._color.base)
            .text(this._data.date, this._x(x), this._y(y));
    }

    _renderVerticalLine(x)
    {
        this._doc
            .setLineWidth(0.35)
            .setDrawColor(this._color.primary)
            .line(
                this._x(x), this._y(),
                this._x(x), this._y(this.height)
            );
    }

    _renderLabel(x, y)
    {
        const label = this._data.label.toUpperCase();
        const charSpace = 0.2;
        const fontSize = 6;
        const pointsToMillimeterFactor = 72/25.6;
        const labelWidth = this._doc.getStringUnitWidth(label) * fontSize / pointsToMillimeterFactor + label.length * charSpace;

        this._doc.setFillColor(this._color.primary).rect(
            this._x(x + 0.1),
            this._y(y),
            4 + labelWidth + 2,
            2 + this._lineSpace,
            'F'
        );

        this._doc
            .setFont('Inter', 'bold')
            .setFontSize(fontSize)
            .setTextColor('#FFFFFF')
            .text(
                label,
                this._x(x + 4), this._y(y + this._textHeight(fontSize) + this._lineSpace / 2),
                { charSpace: charSpace }
            );
    }

    _renderTopic(x, y)
    {
        this._doc
            .setFont('Inter', 'bold')
            .setFontSize(9)
            .setTextColor(this._color.primary)
            .text(this._data.topic || '--', this._x(x), this._y(y));
    }

    _renderSubtopic(x, y)
    {
        this._doc
            .setFont('Inter', 'normal')
            .setFontSize(9)
            .setTextColor(this._color.base)
            .text(this._data.subtopic, this._x(x), this._y(y));
    }

    _renderIcon(filename, x, y) {
        this._doc.addImage(
            this._imageData(filename),
            'PNG',
            this._x(x), this._y(y - 0.6 - this._textHeight()),
            this._textHeight() + 1.2, this._textHeight() + 1.2
        );
    }

    _renderChairman(x, y)
    {
        this._renderIcon('chairman.png', x, y);
        this._doc
            .setFont('Inter', 'normal')
            .setFontSize(9)
            .setTextColor(this._color.base)
            .text(this._data.chairman, this._x(x + 6), this._y(y));
    }

    _renderReader(x, y)
    {
        this._renderIcon('reader.png', x, y);
        this._doc
            .setFont('Inter', 'normal')
            .setFontSize(9)
            .setTextColor(this._color.base)
            .text(this._data.reader, this._x(x + 6), this._y(y));
    }

    /**
     *
     * Helpers
     *
     */
    _x(x = 0)
    {
        return this._origin.x + x;
    }

    _y(y = 0)
    {
        return this._origin.y + y;
    }

    _mergeIntoData(data = {})
    {
        for (let prop in data) {
            this._data[prop] = data[prop];
        }

        return this._data;
    }

    _imageData(image)
    {
        const imageData = fs.readFileSync(`./pdf/images/${image}`);
        return `data:image/png;base64,${imageData.toString('base64')}`
    }

    _textHeight(fontSize = 9)
    {
        const sizeFactor = 2.5 / 9;

        return fontSize * sizeFactor;
    }
}

module.exports = MeetingBlock;
