export default function SelectInput({
    id,
    name,
    value,
    className,
    onChange,
    children,
    ...props
}) {
    return (
        <select
            id={id}
            name={name}
            value={value}
            className={`rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 ${className}`}
            onChange={onChange}
            {...props}
        >
            {children}
        </select>
    );
}
