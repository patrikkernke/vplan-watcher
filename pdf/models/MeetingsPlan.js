const PdfPlan = require("./PdfPlan")
const MeetingBlock = require('./Renderer/MeetingBlock')

class MeetingsPlan extends PdfPlan
{
    contentBlocks(data) {
        let blocks = [];

        data.forEach(meeting => {
            let block = null;

            switch (meeting.type)
            {
                case 'Kongress':
                    block = this._congressBlock(meeting);
                    break;
                case 'Sondervortrag':
                    block = this._specialTalkBlock(meeting);
                    break;
                case 'Gedächtnismahl':
                    block = this._memorialBlock(meeting);
                    break;
                case 'Dienstwoche':
                    block = this._circuitOverseerTalkBlock(meeting);
                    break;
                case 'Öffentliche Zusammenkunft':
                    block = this._publicTalkBlock(meeting);
                    break;
                default:
                    break;
            }

            blocks.push(block);
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

    _publicTalkBlock(meeting)
    {
        const renderBlock = new MeetingBlock();
        const speaker = meeting.schedule[0].speaker || '--';
        const congregation = meeting.schedule[0].congregation || '--';
        const hasWatchtowerStudy = (meeting.schedule[1] && meeting.schedule[1].type === 'WatchtowerStudy');
        const reader = hasWatchtowerStudy ? meeting.schedule[1].reader : null;

        renderBlock.withData({
            date: meeting.date,
            chairman: meeting.chairman || '--',
            reader: reader || '--',
            topic: meeting.schedule[0].topic,
            subtopic: `${speaker}, ${congregation}`
        });

        return renderBlock;
    }

    _circuitOverseerTalkBlock(meeting)
    {
        const renderBlock = new MeetingBlock();
        const circuitOverseer = meeting.schedule[0].circuitOverseer || '--'
        const hasWatchtowerStudy = (meeting.schedule[1] && meeting.schedule[1].type === 'WatchtowerStudy');

        renderBlock.withData({
            date: meeting.date,
            label: meeting.type,
            chairman: meeting.chairman || '--',
            reader: hasWatchtowerStudy ? meeting.schedule[1].reader : null,
            topic: meeting.schedule[0].topic,
            subtopic: `${circuitOverseer}, Kreisaufseher`
        }).withColorStyle('#6D28D9');

        return renderBlock;
    }

    _memorialBlock(meeting)
    {
        const renderBlock = new MeetingBlock();
        const speaker = meeting.schedule[0].speaker || '--';
        const congregation = meeting.schedule[0].congregation || '--';

        renderBlock.withData({
            date: meeting.date,
            label: meeting.type,
            chairman: meeting.chairman || '--',
            topic: meeting.schedule[0].topic,
            subtopic: `${speaker}, ${congregation}`
        }).withColorStyle('#BE185D');

        return renderBlock;
    }

    _specialTalkBlock(meeting)
    {
        const renderBlock = new MeetingBlock();
        const speaker = meeting.schedule[0].speaker || '--';
        const congregation = meeting.schedule[0].congregation || '--';
        const hasWatchtowerStudy = (meeting.schedule[1] && meeting.schedule[1].type === 'WatchtowerStudy');
        const reader = hasWatchtowerStudy ? meeting.schedule[1].reader : null;

        renderBlock.withData({
            date: meeting.date,
            label: meeting.type,
            chairman: meeting.chairman || '--',
            reader: reader || '--',
            topic: meeting.schedule[0].topic,
            subtopic: `${speaker}, ${congregation}`
        }).withColorStyle('#B45309');

        return renderBlock;
    }

    _congressBlock(meeting)
    {
        const block = new MeetingBlock();

        block.withData({
            date: meeting.date,
            label: meeting.type,
            topic: meeting.schedule[0].motto,
        }).withColorStyle('#047857');

        return block;
    }
}

module.exports = MeetingsPlan;
