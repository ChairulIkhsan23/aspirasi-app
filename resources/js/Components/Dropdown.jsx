import { Link } from '@inertiajs/react';
import * as DropdownMenu from '@radix-ui/react-dropdown-menu';

const Dropdown = ({ children }) => {
    return <DropdownMenu.Root>{children}</DropdownMenu.Root>;
};

const Trigger = ({ children }) => {
    return (
        <DropdownMenu.Trigger asChild>
            <div className="cursor-pointer">{children}</div>
        </DropdownMenu.Trigger>
    );
};

const Content = ({
    align = 'end',
    width = '48',
    contentClasses = 'py-1 bg-white',
    children,
}) => {
    let widthClasses = width === '48' ? 'w-48' : '';

    return (
        <DropdownMenu.Portal>
            <DropdownMenu.Content
                sideOffset={4}
                align={align}
                className={`z-50 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 ${widthClasses} ${contentClasses}`}
            >
                {children}
            </DropdownMenu.Content>
        </DropdownMenu.Portal>
    );
};

const DropdownLink = ({ className = '', children, ...props }) => {
    return (
        <DropdownMenu.Item asChild>
            <Link
                {...props}
                className={
                    'block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none ' +
                    className
                }
            >
                {children}
            </Link>
        </DropdownMenu.Item>
    );
};

Dropdown.Trigger = Trigger;
Dropdown.Content = Content;
Dropdown.Link = DropdownLink;

export default Dropdown;
