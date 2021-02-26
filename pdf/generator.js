const MeetingsPlan = require("./models/MeetingsPlan")
const DataSourceReader = require("./models/DataSourceReader")

const plan = new MeetingsPlan();

plan.withData(DataSourceReader.read('weekend-meetings.json'))
    .render()
    .save("pdf/docs/a4.pdf");

require("child_process").exec("open ./pdf/docs/a4.pdf");
