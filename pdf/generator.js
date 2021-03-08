const MeetingsPlan = require("./models/MeetingsPlan")
const DataSourceReader = require("./models/DataSourceReader")
const ServiceMeetingsPlan = require("./models/ServiceMeetingsPlan");


// const publicMeetingsPlan = new MeetingsPlan();
// publicMeetingsPlan.withData(DataSourceReader.read('weekend-meetings.json'))
//     .render()
//     .save(`pdf/docs/vortragsplanung.pdf`);
// require("child_process").exec('open ./pdf/docs/vortragsplanung.pdf');

const serviceMeetingsPlan = new ServiceMeetingsPlan();
serviceMeetingsPlan.withData(DataSourceReader.read('service-meetings.json'))
    .render()
    .save('pdf/docs/treffpunkte.pdf');
require("child_process").exec('open ./pdf/docs/treffpunkte.pdf');
