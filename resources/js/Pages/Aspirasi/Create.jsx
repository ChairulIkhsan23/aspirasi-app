import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import SelectInput from '@/Components/SelectInput';
import TextArea from '@/Components/TextArea';
import TextInput from '@/Components/TextInput';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react'; // Pastikan Link di-import di sini

export default function Create({ auth, topiks }) {
    const { data, setData, post, processing, errors } = useForm({
        judul: '',
        isi: '',
        topik_id: '',
        is_anonim: false,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('aspirasi.store'));
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Buat Aspirasi Baru
                </h2>
            }
        >
            <Head title="Buat Aspirasi" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <form onSubmit={submit}>
                                <div className="mb-4">
                                    <InputLabel
                                        htmlFor="judul"
                                        value="Judul Aspirasi"
                                    />
                                    <TextInput
                                        id="judul"
                                        type="text"
                                        name="judul"
                                        value={data.judul}
                                        className="mt-1 block w-full"
                                        isFocused={true}
                                        onChange={(e) =>
                                            setData('judul', e.target.value)
                                        }
                                        maxLength="255"
                                        required
                                    />
                                    <InputError
                                        message={errors.judul}
                                        className="mt-2"
                                    />
                                </div>

                                <div className="mb-4">
                                    <InputLabel
                                        htmlFor="isi"
                                        value="Isi Aspirasi"
                                    />
                                    <TextArea
                                        id="isi"
                                        name="isi"
                                        value={data.isi}
                                        className="mt-1 block w-full"
                                        rows={6}
                                        onChange={(e) =>
                                            setData('isi', e.target.value)
                                        }
                                        required
                                    />
                                    <InputError
                                        message={errors.isi}
                                        className="mt-2"
                                    />
                                </div>

                                <div className="mb-4">
                                    <InputLabel
                                        htmlFor="topik_id"
                                        value="Topik"
                                    />
                                    <SelectInput
                                        id="topik_id"
                                        name="topik_id"
                                        value={data.topik_id}
                                        className="mt-1 block w-full"
                                        onChange={(e) =>
                                            setData('topik_id', e.target.value)
                                        }
                                        required
                                    >
                                        <option value="">Pilih Topik</option>
                                        {topiks?.map((topik) => (
                                            <option
                                                key={topik.id}
                                                value={topik.id}
                                            >
                                                {topik.nama}
                                            </option>
                                        ))}
                                    </SelectInput>
                                    <InputError
                                        message={errors.topik_id}
                                        className="mt-2"
                                    />
                                </div>

                                <div className="mb-4 flex items-center">
                                    <input
                                        id="is_anonim"
                                        type="checkbox"
                                        name="is_anonim"
                                        checked={data.is_anonim}
                                        className="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        onChange={(e) =>
                                            setData(
                                                'is_anonim',
                                                e.target.checked,
                                            )
                                        }
                                    />
                                    <InputLabel
                                        htmlFor="is_anonim"
                                        value="Kirim sebagai anonim"
                                        className="ml-2"
                                    />
                                    <InputError
                                        message={errors.is_anonim}
                                        className="mt-2"
                                    />
                                </div>

                                <div className="mt-4 flex items-center justify-between">
                                    <Link
                                        href={route('dashboard')}
                                        className="inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition duration-150 ease-in-out hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                                    >
                                        {/* Icon */}
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 16 16"
                                            fill="currentColor"
                                            className="mr-2 size-4"
                                        >
                                            <path
                                                fillRule="evenodd"
                                                d="M12.5 9.75A2.75 2.75 0 0 0 9.75 7H4.56l2.22 2.22a.75.75 0 1 1-1.06 1.06l-3.5-3.5a.75.75 0 0 1 0-1.06l3.5-3.5a.75.75 0 0 1 1.06 1.06L4.56 5.5h5.19a4.25 4.25 0 0 1 0 8.5h-1a.75.75 0 0 1 0-1.5h1a2.75 2.75 0 0 0 2.75-2.75Z"
                                                clipRule="evenodd"
                                            />
                                        </svg>
                                        Kembali ke Dashboard
                                    </Link>

                                    <PrimaryButton
                                        className="ml-4"
                                        disabled={processing}
                                    >
                                        {processing ? 'Menyimpan...' : 'Simpan'}
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
