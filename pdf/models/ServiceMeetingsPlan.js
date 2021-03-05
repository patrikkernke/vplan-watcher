const PdfPlan = require("./PdfPlan")
const ServiceMeetingBlock = require("./Renderer/ServiceMeetingBlock");

class ServiceMeetingsPlan extends PdfPlan
{
    contentBlocks(data)
    {
        let blocks = [];
        const meetingdates = Object.keys(data);

        meetingdates.forEach(keyDate => {
            const block = new ServiceMeetingBlock();
            const year = parseInt(keyDate.split('-')[0]);
            const month = parseInt(keyDate.split('-')[1]) -1;
            const day = parseInt(keyDate.split('-')[2]);

            block.withData({
                date: (new Date(year, month, day)),
                events: data[keyDate]
            });

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
            .text('Zusammenkunft für den Predigtdienst', settings.margin.left, 25,{ lineHeightFactor: 1 });

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

module.exports = ServiceMeetingsPlan;
