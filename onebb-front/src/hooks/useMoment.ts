import moment from 'moment'

export default function useMoment() {

    const config = {
        format: 'YYYY-MM-DD hh:mm:ss'
    }

    const setFormat = (format: string) => {
        config.format = format;
    }

    const parse = (value: string) => {
        return moment(value).format(config.format)
    }
    return {
        setFormat,
        parse,
    }
}