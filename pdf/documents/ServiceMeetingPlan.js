const PdfDocument = require("../pdf-designer/PdfDocument");
const Text = require('../pdf-designer/Element/Text')
const StackVertical = require("../pdf-designer/Element/StackVertical");
const StackHorizontal = require("../pdf-designer/Element/StackHorizontal");
const LineHorizontal = require("../pdf-designer/Element/LineHorizontal");
const LineVertical = require("../pdf-designer/Element/LineVertical");

class ServiceMeetingPlan extends PdfDocument {
    header(data) {

        const months = [
            'Januar', 'Februar', 'März', 'April',
            'Mai', 'Juni', 'Juli', 'August',
            'September', 'Oktober', 'November', 'Dezember'
        ]

        const date = `${months[data.reference.month-1]} ${data.reference.year}`

        return StackHorizontal.create([
            StackVertical.create([
                Text.create('Zusammenkunft für den Predigtdienst').fontSize(16).bold(),
                Text.create(`${date} • Versammlung Neuwied`).leading(2.5),
            ]).fixedWidth(60),
        ]).space(15, this.margin.right, 30, this.margin.left)
    }

    footer() {
        const today = new Date()
        const months = [
            'Januar', 'Februar', 'März', 'April',
            'Mai', 'Juni', 'Juli', 'August',
            'September', 'Oktober', 'November', 'Dezember'
        ]
        const todayString = `${today.getDate()}. ${months[today.getMonth()]} ${today.getFullYear()}`
        const pageNumberString = `Seite ${this.currentPageNumber}/{n}`

        return Text.create(`${pageNumberString} • ${todayString}`).fontSize(8)
            .space(0, this.margin.right, 10, this.margin.left)
    }

    content(data) {
        const daysStack = []

        Object.keys(data.data).forEach(day => {
            daysStack.push(
                StackVertical.create([
                    LineHorizontal.create(this.contentWidth, 0.22)
                        .dashed(0.5, 0.5).color('#888'),
                    StackHorizontal.create([
                        this.getDateStackFor(day, data.reference)
                            .top(2.5).bottom(2.5).right(3),
                        this.getCongregationStack(data.data[day])
                            .top(2.5).bottom(2.5),
                        LineVertical.create(6.5, 0.22)
                            .dashed(0.5, 0.5).color('#888'),
                        this.getGroupStackFor('Irlich', data.data[day]),
                        this.getGroupStackFor('Bendorf 1', data.data[day]),
                        this.getGroupStackFor('Niederbieber', data.data[day]),
                        this.getGroupStackFor('Neuwied 1', data.data[day]),
                        this.getGroupStackFor('Bendorf 2', data.data[day]),
                        this.getGroupStackFor('Türkisch', data.data[day]),
                        this.getGroupStackFor('Neuwied 2', data.data[day]),
                    ]),
                ])
            )
        })

        return daysStack
    }

    getDateStackFor(day, reference)
    {
        const date = new Date(reference.year, reference.month, parseInt(day))
        const weekDay = ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'][date.getDay()]
        const calenderDay = day.padStart(2, "0");
        const month = reference.month.toString().padStart(2, "0")

        return StackHorizontal.create([
            Text.create(`${weekDay}`).fontSize(8).leading(1).bold().fixedWidth(6),
            Text.create(`${calenderDay}.${month}`).fontSize(8).leading(1).fixedWidth(10)
        ])
    }

    getCongregationStack(meetings)
    {
        const stack = []
        const congregation = meetings.filter(meeting => {
            return meeting.type === 'congregation'
        })

        congregation.forEach(meeting => {
            const date = new Date(meeting.start_at)
            const hours = date.getHours().toString().padStart(2, "0")
            const minutes = date.getMinutes().toString().padStart(2, "0")
            stack.push(StackHorizontal.create([
                Text.create(`${hours}:${minutes}`)
                    .fontSize(8).leading(1).fixedWidth(10),
                Text.create(`${meeting.leader}`)
                    .fontSize(8).leading(1).fixedWidth(25),
            ]))
        })

        while (stack.length < 2) {
            stack.push(StackHorizontal.create([
                Text.create('')
                    .fontSize(8).leading(1).fixedWidth(10),
                Text.create('')
                    .fontSize(8).leading(1).fixedWidth(25),
            ]))
        }

        return StackHorizontal.create(stack);
    }

    getGroupStackFor(groupName, meetings)
    {
        const groupMeetings = meetings.filter(meeting => {
            if (! meeting.fieldServiceGroup) return false

            return meeting.fieldServiceGroup.name === groupName
        })

        let textElement = Text.create('')
            .fontSize(8).leading(1)
            .fixedWidth(this.groupWidth).fixedHeight(6.95)
            .backgroundColor('#EEEEEE')

        if (groupMeetings.length >= 1) {
            const date = new Date(groupMeetings[0].start_at)
            const hours = date.getHours().toString().padStart(2, "0")
            const minutes = date.getMinutes().toString().padStart(2, "0")
            textElement = Text.create(`${hours}:${minutes}`)
                .fontSize(8).leading(1).top(2.5).bottom(2.5)
        }


        const space = (this.groupWidth - textElement.in(this._document).width) / 2

        return StackHorizontal.create([
            textElement.left(space).right(space),
            LineVertical.create(6.5, 0.22)
                .dashed(0.5, 0.5).color('#888')
        ]).fixedWidth(this.groupWidth);
    }

    get groupWidth() {
        return (this.contentWidth - 89.5) / 7
    }
}

module.exports = ServiceMeetingPlan
