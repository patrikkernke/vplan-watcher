const PdfPlan = require("./PdfPlan")

class WeekendMeetingsPlan extends PdfPlan {

    render() {

        this._data.forEach(meeting => {

            this._nextRow()

            // Date Block
            this._doc
                .setFont('Inter', 'normal')
                .setFontSize(9)
                .setTextColor(0, 0, 0)
                .text(meeting.date, this._margin.left, this._currentRowY());

            this._doc
                .setLineWidth(0.35)
                .line(
                    this._margin.left+16, this._currentRowY()-5,
                    this._margin.left+16, this._currentRowY()+7
                );

            // Content Block
            this._doc
                .setFont('Inter', 'bold')
                .setFontSize(9)
                .setTextColor(0, 0, 0)
                .text(meeting.schedule[0].topic, this._margin.left+20, this._currentRowY());

            this._doc
                .setFont('Inter', 'normal')
                .setFontSize(9)
                .setTextColor(0, 0, 0)
                .text(`${meeting.schedule[0].speaker}, ${meeting.schedule[0].congregation}`, this._margin.left+20, this._currentRowY()+4.6);

            // Subinfo Block
            if (! meeting.chairman) {
                meeting.chairman = '--'
            }

            this._doc.addImage(
                this._imageData('chairman.png'), 'PNG',
                this._margin.left+150, this._currentRowY()-3.2,
                3.8, 3.8
            );

            this._doc
                .setFont('Inter', 'normal')
                .setFontSize(9)
                .setTextColor(0, 0, 0)
                .text(meeting.chairman, this._margin.left+156, this._currentRowY());

            if (meeting.schedule[1].reader) {

                this._doc.addImage(
                    this._imageData('reader.png'), 'PNG',
                    this._margin.left+150, this._currentRowY()+1.4,
                    3.8, 3.8
                );

                this._doc
                    .setFont('Inter', 'normal')
                    .setFontSize(9)
                    .setTextColor(0, 0, 0)
                    .text(meeting.schedule[1].reader, this._margin.left+156, this._currentRowY()+4.6);
            }
        })

        return super.render();
    }

    _pageHeader() {

        // Title
        this._doc
            .setFont('Inter', 'bold')
            .setFontSize(18)
            .setTextColor(0, 0, 0)
            .text('Zusammenkunft für die Öffentlichkeit', this._margin.left, this._margin.top,{ lineHeightFactor: 1 });

        // Date
        const today = new Date();

        const months = [
            'Januar', 'Februar', 'März', 'April',
            'Mai', 'Juni', 'Juli', 'August',
            'September', 'Oktober', 'November', 'Dezember'
        ];

        const todayString = `${today.getDate()}. ${months[today.getMonth()]} ${today.getFullYear()}`
        const pageNumberString = `Seite ${this._doc.getCurrentPageInfo().pageNumber}/${this._doc.getNumberOfPages()}`;

        const subtitle = this._doc.getNumberOfPages() > 1
            ? `${pageNumberString} • ${todayString}`
            : todayString;

        this._doc
            .setFont('Inter', 'normal')
            .setFontSize(9)
            .setTextColor(100, 100, 100)
            .text(subtitle, this._margin.left, 30);
    }

}

module.exports = WeekendMeetingsPlan;
