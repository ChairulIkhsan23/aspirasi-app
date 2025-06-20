export default function TextArea({
    id,
    name,
    value,
    className,
    rows = 3,
    onChange,
    ...props
}) {
    return (
        <textarea
            id={id}
            name={name}
            value={value}
            rows={rows}
            className={`rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 ${className}`}
            onChange={onChange}
            {...props}
        />
    );
}
