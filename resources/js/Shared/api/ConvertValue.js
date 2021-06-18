export function formatCurrency(value) {
    const formatter = new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
    });

    return formatter.format(value);
}

export function formatPercentage(value, precision, string = false) {
    let negative = false;

    if (precision === undefined) {
        precision = 0;
    }

    if (value < 0) {
        negative = true;
        value = value * -1;
    }

    let multiplier = Math.pow(10, precision);
    value = parseFloat((value * multiplier).toFixed(11));
    value = (Math.round(value) / multiplier).toFixed(precision);

    if (negative) {
        value = (value * -1).toFixed(precision);
    }

    if (string) {
        value = value + "%";
    }

    return value;
}
