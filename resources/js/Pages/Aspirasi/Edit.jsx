import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import SelectInput from '@/Components/SelectInput';
import TextArea from '@/Components/TextArea';
import TextInput from '@/Components/TextInput';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import axios from 'axios';
import { toast } from 'react-toastify';
import { route } from 'ziggy-js';

export default function Edit({ auth, aspirasi, topiks }) {
    const { data, setData, patch, processing, errors } = useForm({
        judul: aspirasi.judul || '',
        isi: aspirasi.isi || '',
        topik_id: aspirasi.topik_id || '',
        is_anonim: aspirasi.is_anonim || false,
        status: aspirasi.status || 'pending',
    });

    const submit = async (e) => {
        e.preventDefault();

        if (!auth.user) {
            window.location.href = route('login');
            return;
        }

        try {
            await axios.get('/sanctum/csrf-cookie');

            patch(route('aspirasi.update', aspirasi.id), {
                onSuccess: () => {
                    toast.success('Berhasil diperbarui.');
                },
                onError: () => {
                    toast.error('Gagal memperbarui.');
                },
                preserveScroll: true,
            });
        } catch (error) {
            toast.error('Gagal memuat CSRF token atau sesi kadaluarsa.');
            console.error('Error:', error);
        }
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="text-xl font-semibold text-gray-800">
                    Edit Aspirasi
                </h2>
            }
        >
            <Head title="Edit Aspirasi" />
            <div className="py-12">
                <div className="mx-auto max-w-3xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden rounded-lg bg-white shadow-sm">
                        <div className="p-6 text-gray-900">
                            <form onSubmit={submit} className="space-y-4">
                                {/* Judul */}
                                <div>
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
                                        isFocused
                                        onChange={(e) =>
                                            setData('judul', e.target.value)
                                        }
                                        required
                                    />
                                    <InputError
                                        message={errors.judul}
                                        className="mt-2"
                                    />
                                </div>

                                {/* Isi */}
                                <div>
                                    <InputLabel
                                        htmlFor="isi"
                                        value="Isi Aspirasi"
                                    />
                                    <TextArea
                                        id="isi"
                                        name="isi"
                                        value={data.isi}
                                        className="mt-1 block w-full"
                                        rows={5}
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

                                {/* Topik */}
                                <div>
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
                                        {topiks.map((topik) => (
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

                                {/* Lampiran */}
                                {aspirasi.lampiran && (
                                    <div className="text-sm text-gray-600">
                                        Lampiran:{' '}
                                        <a
                                            href={`/storage/${aspirasi.lampiran}`}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="text-blue-600 underline"
                                        >
                                            Lihat Lampiran
                                        </a>
                                    </div>
                                )}

                                {/* Anonim */}
                                <div className="flex items-center">
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
                                </div>

                                {/* Status (hanya untuk admin) */}
                                {auth.user.role === 'admin' && (
                                    <div>
                                        <InputLabel
                                            htmlFor="status"
                                            value="Status"
                                        />
                                        <SelectInput
                                            id="status"
                                            name="status"
                                            value={data.status}
                                            className="mt-1 block w-full"
                                            onChange={(e) =>
                                                setData(
                                                    'status',
                                                    e.target.value,
                                                )
                                            }
                                            required
                                        >
                                            <option value="pending">
                                                Pending
                                            </option>
                                            <option value="diproses">
                                                Diproses
                                            </option>
                                            <option value="selesai">
                                                Selesai
                                            </option>
                                            <option value="ditolak">
                                                Ditolak
                                            </option>
                                        </SelectInput>
                                        <InputError
                                            message={errors.status}
                                            className="mt-2"
                                        />
                                    </div>
                                )}

                                <div className="flex justify-end">
                                    <PrimaryButton disabled={processing}>
                                        {processing
                                            ? 'Menyimpan...'
                                            : 'Simpan Perubahan'}
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
