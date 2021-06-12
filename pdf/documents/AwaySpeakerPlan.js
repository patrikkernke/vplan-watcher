const PdfDocument = require("../pdf-designer/PdfDocument");
const Text = require('../pdf-designer/Element/Text')
const StackVertical = require("../pdf-designer/Element/StackVertical");
const StackHorizontal = require("../pdf-designer/Element/StackHorizontal");
const LineHorizontal = require("../pdf-designer/Element/LineHorizontal");

class AwaySpeakerPlan extends PdfDocument {
    header() {

        return StackHorizontal.create([
            StackVertical.create([
                Text.create('Neuwied').fontSize(16).bold(),
                Text.create('Auswärtsredner').leading(2.5),
            ]).fixedWidth(60),
            StackVertical.create([
                Text.create('Öffentl. Zusammenkunft').bold(),
                Text.create('Jeden Sonntag um 10:00 Uhr').leading(2.5),
                Text.create('Übertragung via Zoom').leading(1.8),
            ]).top(2.85).right(10),
            StackVertical.create([
                Text.create('Gehilfe Vortragsko.').bold(),
                Text.create('Patrik Kernke').leading(2.5),
                Text.create('PKernke@jwpub.org').leading(1.8),
                Text.create('0160 99 400 66 5').leading(1.8),
            ]).top(2.85)
        ]).space(15, this.margin.right, 5, this.margin.left)
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

        return Text.create(`${pageNumberString} • ${todayString}`)
            .space(0, this.margin.right, 10, this.margin.left)
    }

    content(data) {
        let speakers = []

        data.forEach(speaker => speakers.push(this.speakerBlock(speaker)))

        return speakers
    }

    speakerBlock(speaker) {
        const speakerInfo = StackVertical.create([
            Text.create(`${speaker.firstname} ${speaker.lastname}`).bold().leading(1).bottom(1.5)
        ])

        const tags = StackHorizontal.create().top(1);
        if (! speaker.may_give_speak_away) {
            tags.append(
                StackVertical.create([
                    Text.create('Nicht aktiv').space(1,1.5).backgroundColor('#FEF2F2').color('#991B1B').leading(1).fontSize(7)
                ]).right(1)
            )
        }

        if (speaker.is_dag) {
            tags.append(
                Text.create('Dienstamtgehilfe').space(1,1.5).backgroundColor('#F1F5F9').color('#334155').leading(1).fontSize(7).right(2)
            )
        }

        speakerInfo.append(tags)

        if (speaker.email) {
            speakerInfo.append( Text.create(speaker.email).fontSize(7).leading(2).color('#4C1D95') )
        }

        if (speaker.phone) {
            speakerInfo.append( Text.create(speaker.phone).fontSize(7).leading(2) )
        }

        if (speaker.notes) {
            speakerInfo.append( Text.create(speaker.notes).fontSize(7).leading(1.3).top(3).maxWidth(50) )
        }

        const dispositions = StackVertical.create()
        speaker.dispositions.forEach((dispo, index, dispos) => {
            const block = this.dispositionBlock(dispo.topic_id, dispo.topic, index < dispos.length -1)
            dispositions.append(block)
        })

        return StackVertical.create([
            LineHorizontal.create(this.contentWidth).thickness(0.3),
            StackHorizontal.create([
                speakerInfo.fixedWidth(60).space(2.5, 2.5, 3),
                dispositions
            ])
        ])
    }

    dispositionBlock(disposition, topic, divider = true) {
        const stack = StackVertical.create([
            StackHorizontal.create([
                Text.create(disposition).leading(1).fixedWidth(15),
                Text.create(topic).leading(1),
            ]).space(2.3),
        ])

        if (divider) stack.append(
            LineHorizontal.create(this.contentWidth - 60)
                .thickness(0.22).color('#BBB')
        )

        return stack
    }

}

module.exports = AwaySpeakerPlan
