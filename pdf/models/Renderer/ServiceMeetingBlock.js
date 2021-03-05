const fs = require("fs")

class ServiceMeetingBlock
{
    constructor()
    {
        this._doc = null;
        this._origin = { x: 0, y: 0 };
        this._data = {
            date: null,
            events: []
        };

        this._color = {
            primary: '#000000',
            weekday: '#888888',
            base: '#000000',
            service_week: '#6D28D9',
            visit_service_overseer: '#B45309'
        }

        this._safeSpace = 2;
        this._lineSpace = 4;
    }

    /**
     * Provide jsPDF block in which the work should be done
     * @param doc
     * @returns {ServiceMeetingBlock}
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
     * @returns {ServiceMeetingBlock}
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
     * @returns {ServiceMeetingBlock}
     */
    withData(data = {})
    {
        this._mergeIntoData(data);
        return this;
    }

    /**
     * Render the block in the document
     * @returns {ServiceMeetingBlock}
     */
    render()
    {
        if (! this._doc) throw new Error('Provide a jsPDF-Object in which the block should be rendered.');
        if (! this._data) throw new Error('Provide data for the block.');
        if (! this._data.date) throw new Error('Provide at minimum a date for the block.');

        const firstLineY = this._safeSpace + this._textHeight();
        // this._renderBackground();

        this._renderDate(0, firstLineY);

        this._renderEvents(30, firstLineY);
        this._renderVerticalLine(19);

        return this;
    }

    get height()
    {
        const minHeight = this._safeSpace + this._textHeight() + this._safeSpace + this._textHeight(6) +  this._safeSpace;

        let countLines = this._data.events.length;

        const sumTextHeight = countLines * this._textHeight();
        const sumLineSpace = (countLines - 1) * this._lineSpace;

        const calculatedHeight = sumTextHeight + sumLineSpace + this._safeSpace*2

        return  minHeight > calculatedHeight
            ? minHeight
            : calculatedHeight;
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
        const shortMonthName = [
            'Jan.', 'Feb.', 'Mär.', 'Apr.',
            'Mai', 'Jun.', 'Jul.', 'Aug.',
            'Sep.', 'Okt.', 'Nov.', 'Dez.'
        ][this._data.date.getMonth()];

        const weekdayName = [
            'Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'
        ][this._data.date.getDay()];

        const formattedDate = `${this._data.date.getDate()}. ${shortMonthName}`;

        this._doc
            .setFont('Inter', 'normal')
            .setFontSize(9)
            .setTextColor(this._color.base)
            .text(formattedDate, this._x(x), this._y(y));

        this._doc
            .setFont('Inter', 'bold')
            .setFontSize(6)
            .setTextColor(this._color.weekday)
            .text(weekdayName.toUpperCase(), this._x(x), this._y(y) + this._safeSpace + this._textHeight(6), {
                charSpace: 0.1
            });
    }

    _renderEvents(x, y)
    {
        let currentX = x;
        let currentY = y;

        this._data.events.forEach(event => {

            this._doc
                .setFont('Inter', 'normal')
                .setFontSize(9)
                .setTextColor(this._color.base)
                .text(event.time == '00:00' ? '?' : event.time, this._x(currentX), this._y(currentY), {
                    align: "right"
                });

            if (event.type == 'congregation') {
                this._doc
                    .setFont('Inter', 'bold')
                    .setFontSize(9)
                    .setTextColor(this._color.base)
                    .text('Versammlung', this._x(currentX) + 4, this._y(currentY));
            }

            if (event.type == 'field_service_group') {
                this._doc
                    .setFont('Inter', 'normal')
                    .setFontSize(9)
                    .setTextColor(this._color.base)
                    .text(event.field_service_group.name, this._x(currentX) + 4, this._y(currentY));
            }

            if (event.type == 'service_week') {
                this._doc
                    .setFont('Inter', 'bold')
                    .setFontSize(9)
                    .setTextColor(this._color.service_week)
                    .text('Dienstwoche', this._x(currentX) + 4, this._y(currentY));

                this._doc
                    .setLineWidth(1)
                    .setDrawColor(this._color.service_week)
                    .line(
                        this._x(currentX - 11 - 0.35), this._y(currentY - this._textHeight() - this._safeSpace),
                        this._x(currentX) - 11 - 0.35, this._y(currentY + this._safeSpace),
                    );
            }

            this._renderIcon('zoom.png', currentX + 34, currentY);
            this._doc
                .setFont('Inter', 'normal')
                .setFontSize(9)
                .setTextColor(this._color.base)
                .textWithLink(event.zoom.id, this._x(currentX) + 40, this._y(currentY), {
                    url: event.zoom.link,
                });

            this._renderIcon('password.png', currentX + 66, currentY);
            this._doc
                .setFont('Inter', 'normal')
                .setFontSize(9)
                .setTextColor(this._color.base)
                .text(event.zoom.password, this._x(currentX) + 72, this._y(currentY));

            if (event.leader) {
                this._renderIcon('chairman.png', currentX + 100, currentY);
                this._doc
                    .setFont('Inter', 'normal')
                    .setFontSize(9)
                    .setTextColor(this._color.base)
                    .text(event.leader, this._x(currentX) + 106, this._y(currentY));
            }

            if (event.is_visit_service_overseer) {
                this._renderIcon('service_overseer.png', currentX + 100, currentY);
                this._doc
                    .setFont('Inter', 'normal')
                    .setFontSize(9)
                    .setTextColor(this._color.visit_service_overseer)
                    .text('Dienstaufseher', this._x(currentX) + 106, this._y(currentY));

                this._doc
                    .setLineWidth(1)
                    .setDrawColor(this._color.visit_service_overseer)
                    .line(
                        this._x(currentX - 11 - 0.35), this._y(currentY - this._textHeight() - this._safeSpace),
                        this._x(currentX) - 11 - 0.35, this._y(currentY + this._safeSpace),
                    );
            }

            currentY = currentY + this._lineSpace + this._textHeight();
        });
    }

    _renderIcon(filename, x, y) {
        this._doc.addImage(
            this._imageData(filename),
            'PNG',
            this._x(x), this._y(y) - 0.6 - this._textHeight(),
            this._textHeight() + 1.2, this._textHeight() + 1.2
        );
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

module.exports = ServiceMeetingBlock;
