import { initFlowbite } from "flowbite";
import { Link } from "@inertiajs/vue3";
export default (props) => {
    return {
        components: {
            Link,
        },
        mounted() {
            initFlowbite();
        },
        data() {
            return {
                i18n_locale: props.initialPage.props.locale,
                user: props.initialPage.props.auth?.user || { id: 1 },
            };
        },
        methods: {
            downloadCSVData(
                filename,
                headers,
                data,
                add_expoerttime_row = false
            ) {
                /* Example input:
                        headers: ['Name', 'Age', 'Gender']; array of headers
                        data: [
                            ['Ahamd', '18', 'Male'],
                            ['Ibraihm', '35', 'Male'],
                            ['Killua', '12', 'Male'],
                        ]; array of arrays of rows
                */
                filename = filename || "download";
                let now = new Date();
                let csv = add_expoerttime_row
                        ? `Exported At: ${
                              now.getFullYear() +
                              "-" +
                              String(now.getMonth() + 1).padStart(2, "0") +
                              "-" +
                              String(now.getDate()).padStart(2, "0") +
                              " " +
                              String(now.getHours()).padStart(2, "0") +
                              ":" +
                              String(now.getMinutes()).padStart(2, "0") +
                              ":" +
                              String(now.getSeconds()).padStart(2, "0")
                          }\n`
                        : "",
                    seperator = ",";
                headers.forEach((header) => {
                    csv += header + seperator;
                });
                csv = csv.substring(0, csv.lastIndexOf(seperator)) + "\n";

                data.forEach((row) => {
                    csv += row.join(",");
                    csv += "\n";
                });

                const anchor = document.createElement("a");
                anchor.href =
                    "data:text/csv;charset=utf-8," + encodeURIComponent(csv);
                anchor.target = "_blank";
                anchor.download = `${filename}.csv`;
                anchor.click();
            },
        },
    };
};
