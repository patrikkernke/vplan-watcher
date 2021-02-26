const PdfPlan = require("./PdfPlan")
const MeetingBlock = require('./Renderer/MeetingBlock')

class MeetingsPlan extends PdfPlan
{
    contentBlocks(data) {
        let blocks = [];

        data.forEach(meeting => {
            const renderBlock = new MeetingBlock();

            renderBlock.withData({
                date: meeting.date,
                chairman: meeting.chairman,
                reader: (meeting.schedule[1] && meeting.schedule[1].type === 'WatchtowerStudy')
                    ? meeting.schedule[1].reader
                    : null,
                topic: meeting.schedule[0].topic,
            });

            if (meeting.schedule[0].type === 'PublicTalk' || meeting.schedule[0].type === 'SpecialTalk') {
                const speaker = meeting.schedule[0].speaker || '--';
                const congregation = meeting.schedule[0].congregation || '--';
                renderBlock.withData({ subtopic: `${speaker}, ${congregation}` });
            }

            if (meeting.schedule[0].type === 'CircuitOverseerTalk') {
                const circuitOverseer = meeting.schedule[0].circuitOverseer || '--'
                renderBlock.withData({ subtopic: `${circuitOverseer}, Kreisaufseher` });
            }

            if (meeting.schedule[0].type === 'SpecialTalk') {
                renderBlock.withColorStyle('#6D28D9');
                renderBlock.withData({ label: 'Sondervortrag'})
            }

            if (meeting.schedule[0].type === 'CircuitOverseerTalk') {
                renderBlock.withColorStyle('#047857');
                renderBlock.withData({ label: 'Dienstwoche'})
            }

            blocks.push(renderBlock);
        })

        return blocks;
    }

    pageLayout(page, settings, pageNumber, numberOfPages)
    {
        // Title
        page.setFont('Inter', 'bold')
            .setFontSize(18)
            .setTextColor('#000000')
            .text('Zusammenkunft für die Öffentlichkeit', settings.margin.left, 25,{ lineHeightFactor: 1 });

        // Date
        const today = new Date();
        const months = [
            'Januar', 'Februar', 'März', 'April',
            'Mai', 'Juni', 'Juli', 'August',
            'September', 'Oktober', 'November', 'Dezember'
        ];

        const todayString = `${today.getDate()}. ${months[today.getMonth()]} ${today.getFullYear()}`
        const pageNumberString = `Seite ${pageNumber}/${numberOfPages}`;

        const subtitle = numberOfPages > 1
            ? `${pageNumberString} • ${todayString}`
            : todayString;

        page.setFont('Inter', 'normal')
            .setFontSize(9)
            .setTextColor('#000000')
            .text(subtitle, settings.margin.left, 30);
    }
}

module.exports = MeetingsPlan;
