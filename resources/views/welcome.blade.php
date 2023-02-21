<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Biodata</title>
    @vite('resources/css/app.css')
</head>

<body class="">
    <section class="w-3/4 mx-auto mt-8 rounded-3xl">
        <div class="w-full bg-black rounded-t-3xl">
            <h3 class="text-white text-4xl py-3 text-center font-bold">Biodata Form</h3>
        </div>
        <div class="rounded-b-3xl w-full bg-white px-6 py-6 shadow-lg shadow-gray-100">
            <form>
                <h4 class="text-xl text-black font-semibold">Name</h4>
                <div class="flex justify-between px-4 pt-4 gap-4">
                    <div>
                        <input type="text" name="title"
                            class='h-10 rounded-2xl text-center border-2 text-xl text-black w-40' />
                        <p class="text-sm text-center text-gray-400">Title</p>
                    </div>
                    <div>
                        <input type="text" name="firstname"
                            class='h-10 rounded-2xl text-center border-2 text-xl text-black' />
                        <p class="text-sm text-center text-gray-400">Firstname</p>
                    </div>
                    <div>
                        <input type="text" name="lastname"
                            class='h-10 rounded-2xl text-center border-2 text-xl text-black' />
                        <p class="text-sm text-center text-gray-400">Lastname</p>
                    </div>

                </div>
                <h4 class="text-xl text-black font-semibold">Details</h4>
                <div class="flex justify-between px-4 pt-4">
                    <div>
                        <input type="date" name="date of birth"
                            class='h-10 rounded-2xl text-center border-2 text-xl text-black' />
                        <p class="text-sm text-center text-gray-400">Date of Birth</p>
                    </div>
                    <div>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold" for="sex">
                            Sex
                        </label>
                        <select
                            class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-2 px-4 pr-6 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            name="sex" id="grid-state">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="undisclosable">Can't Disclose</option>
                        </select>
                    </div>
                    <div>
                        <input type="text" name="religion"
                            class='h-10 rounded-2xl text-center border-2 text-xl text-black' />
                        <p class="text-sm text-center text-gray-400">Religion</p>
                    </div>

                </div>
                <div class="flex justify-between px-4 pt-8">
                    <div>
                        <input type="text" name="nationality"
                            class='h-10 rounded-2xl text-center border-2 text-xl text-black' />
                        <p class="text-sm text-center text-gray-400">Nationality</p>
                    </div>
                    <div>
                        <input type="text" name="occupation"
                            class='h-10 rounded-2xl text-center border-2 text-xl text-black' />
                        <p class="text-sm text-center text-gray-400">Occupation</p>
                    </div>
                    <div>

                        <input type="text" name="race"
                            class='h-10 rounded-2xl text-center border-2 text-xl text-black' />
                        <p class="text-sm text-center text-gray-400">Race</p>
                    </div>

                </div>
                <h4 class="text-xl text-black font-semibold">Contact</h4>
                <div class="flex justify-between px-4 pt-4">
                    <div>
                        <input type="tel" name="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{3}"
                            placeholder="+234-123-123"
                            class='h-10 rounded-2xl text-center border-2 text-xl text-black' />
                        <p class="text-sm text-center text-gray-400">Phone Number</p>
                    </div>
                    <div>
                        <input type="text" name="email"
                            class='h-10 rounded-2xl text-center border-2 text-xl text-black' />
                        <p class="text-sm text-center text-gray-400">Email</p>
                    </div>
                    <div>

                        <input type="text" name="address"
                            class='h-10 rounded-2xl text-center border-2 text-xl text-black' />
                        <p class="text-sm text-center text-gray-400">Address</p>
                    </div>

                </div>
                <div class="flex justify-center items-center mt-6">
                    <button type="submit"
                        class="h-16 w-56 text-white font-semibold bg-blue-900 rounded-3xl hover:border-2 hover:border-red-500">Submit</button>
                </div>

            </form>
        </div>
    </section>

</body>

</html>
