const PdfPlan = require("./PdfPlan")
const MeetingBlock = require('./Renderer/MeetingBlock')

class MeetingsPlan extends PdfPlan {

    render() {

        this._data.forEach(meeting => {

            this._nextRow()

            const renderBlock = new MeetingBlock();
            renderBlock
                .in(this._doc)
                .at(this._margin.left, this._currentRowY())
                .withData({
                    date: meeting.date,
                    chairman: meeting.chairman,
                    reader: (meeting.schedule[1] && meeting.schedule[1].reader) ? meeting.schedule[1].reader : null,
                    topic: meeting.schedule[0].topic,
                });

            if (meeting.schedule[0].type === 'CircuitOverseerTalk') {
                const circuitOverseer = meeting.schedule[0].circuitOverseer || '--'
                renderBlock.withData({ subtopic: `${circuitOverseer}, Kreisaufseher` });
            }

            renderBlock.render()

            // Content Block


            if (meeting.schedule[0].type === 'CircuitOverseerTalk') {
                if (! meeting.schedule[0].circuitOverseer) meeting.schedule[0].circuitOverseer = '--';
                this._doc.text(`${meeting.schedule[0].circuitOverseer}, Kreisaufseher`, this._margin.left+20, this._currentRowY()+4.6);
            }

            if (meeting.schedule[0].type === 'PublicTalk' || meeting.schedule[0].type === 'SpecialTalk') {
                if (! meeting.schedule[0].speaker) meeting.schedule[0].speaker = '--';
                this._doc.text(`${meeting.schedule[0].speaker}, ${meeting.schedule[0].congregation}`, this._margin.left+20, this._currentRowY()+4.6);
            }

            // chairman icon
            this._doc.addImage(
                this._imageData('chairman.png'), 'PNG',
                this._margin.left+150, this._currentRowY()-3.2,
                3.8, 3.8
            );

            if (meeting.schedule[1] && meeting.schedule[1].reader) {

                this._doc.addImage(
                    this._imageData('reader.png'), 'PNG',
                    this._margin.left+150, this._currentRowY()+1.4,
                    3.8, 3.8
                );
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

    _getColorForType(type) {
        if (type === 'SpecialTalk') {
            return '#6D28D9';
        }

        if (type === 'CircuitOverseerTalk') {
            return '#047857';
        }

        return '#000000';
    }
}

module.exports = MeetingsPlan;
