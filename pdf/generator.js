const MeetingsPlan = require("./models/MeetingsPlan")
const DataSourceReader = require("./models/DataSourceReader")

const plan = new MeetingsPlan(
    DataSourceReader.read('weekend-meetings.json')
)
plan.render().save("pdf/docs/a4.pdf");

require("child_process").exec("open ./pdf/docs/a4.pdf");
