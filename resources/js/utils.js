import moment from "moment";

export const cleaningNumber = (value) => value?.toString().replace(/\./g, "");

// export const numbersOnly = (value) => value.replace(/[^\d]/g, "");
export const numbersOnly = (value) => value.replace(/\./g, '').replace(',', '.').replace("Rp", "");

// contoh formatCurrency(20000, ".");
/* Fungsi formatRupiah */
export const formatCurrency = (value, prefix) => {
    let separator = null;
    let number_string = value
        .toString()
        .replace(/[^,\d]/g, "")
        .toString(),
        split = number_string.split(","),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
        separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    // return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    return prefix == undefined ? rupiah : rupiah ? rupiah : "";
};

export const formatNumberId = (number) => {
    // Menggunakan metode toLocaleString dengan opsi 'id-ID'
    return number.toLocaleString('id-ID');
}

export const convertMonthToRoman = (month) => {
    const romanNumerals = ["", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
    return romanNumerals[month];
}

export const isMobile = () => {
    if (screen.width <= 760) {
        return true;
    } else {
        return false;
    }
}

export const checkNull = (value) => {
    if (
        value === null || value === "" || value == 0 || value === undefined
    ) return null;

    return value;
}

export const limitSentence = (sentence) => {
    const maxLength = 35;

    if (sentence.length > maxLength) {
        sentence = sentence.substring(0, maxLength) + "...";
    }

    return sentence;
};

/**
    Calculate the duration in days between two Moment.js dates.
    @param {moment} date_start - The start date with format YYYY-MM-DD.
    @param {moment} date_end - The end date with format YYYY-MM-DD.
    @returns {number} - The duration in days.
*/
export const dateDuration = (date_start, date_end) => {
    let getDateStart = moment(date_start);
    let getDateEnd = moment(date_end);

    // console.info(getDateEnd);

    let duration = moment.duration(getDateEnd.diff(getDateStart));
    let days = duration.asDays();

    return days;
}

export const datetimeDuration = (datetime_start, datetime_end, is_readable = false) => {
    // const duration = moment.duration(moment(datetime_end).diff(datetime_start));
    const start = moment(datetime_start);
    const end = moment(datetime_end);
    const totalMinutes = end.diff(start, 'minutes');
    const duration = moment.duration(totalMinutes, 'minutes');

    if (!is_readable) {
        return totalMinutes;
    } else {
        const days = duration.days();
        const hours = duration.hours();
        const minutes = duration.minutes();

        let durationString = '';

        if (days > 0) {
            durationString += `${days} hari `;
        }
        if (hours > 0) {
            durationString += `${hours} jam `;
        }
        if (minutes > 0) {
            durationString += `${minutes} menit`;
        }

        return durationString;
    }
}

export const imageToBase64 = (file) => {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onloadend = () => {
            resolve(reader.result);
        };
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });
}

export const listStatus = {
    finish: {
        status: "finish",
        status_last: "active",
    },
    active: {
        status: "active",
        status_last: "pending",
    },
    overtime_finish: {
        status: "active",
        status_last: "overtime",
    },
    correction_finish: {
        status: "finish",
        status_last: "correction",
    },
    // assessment_finish: {
    //     status: "finish",
    //     status_last: "assessment",
    // },
};
