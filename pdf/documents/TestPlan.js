const PdfDocument = require("../pdf-designer/PdfDocument");
const Text = require('../pdf-designer/Element/Text')
const StackVertical = require("../pdf-designer/Element/StackVertical");
const StackHorizontal = require("../pdf-designer/Element/StackHorizontal");

class TestPlan extends PdfDocument {

    content(data, pages) {
        return [
            StackHorizontal.create([
                Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').color('#ffffff').backgroundColor('#550000').leading(1).space(2, 2, 4, 10),
                Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').color('#ffffff').backgroundColor('#550000').leading(1).space(2, 2, 4, 10),
            ]),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').fontSize(40),
        ]
    }

}

module.exports = TestPlan
