export function formatCurrency(value) {
    const formatter = new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
    });

    return formatter.format(value);
}

export function formatPercentage(
    value,
    precision,
    string = false,
    convert = false
) {
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
    if (convert) {
        value = value * 100;
    }
    value = (Math.round(value) / multiplier).toFixed(precision);

    if (negative) {
        value = (value * -1).toFixed(precision);
    }

    if (string) {
        value = value + "%";
    }

    return value;
}

export function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

export async function replaceSymbol(string) {
    let symbols = getBraceContent(string);
    let res = await axios.post("/api/brace-content", { data: symbols });
    res.data.forEach((result) => {
        if (!result.svg) {
            return;
        }

        string = string.replace(
            result.symbolText,
            `
                <svg width="16" height="16" class="inline">
                    <image xlink:href="${result.svg}" width="16" height="16"/>
                </svg>
            `
        );
    });
    return string;
}

export function getBraceContent(string) {
    let found = [];
    let rxp = /{([^}]+)}/g;
    let curMatch;

    while ((curMatch = rxp.exec(string))) {
        found.push(curMatch[1]);
    }

    return found;
}
