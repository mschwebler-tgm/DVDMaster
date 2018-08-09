@extends('layouts.app')

@section('content')
    <div class="md-layout md-gutter md-alignment-center-center">
        <div style="max-width: 670px; padding: 15px;" class="md-elevation-4 md-layout">
            <div class="md-layout-item md-size-50 md-xsmall-size-100">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <span class="md-headline">{{ __('Register') }}</span>
                    <md-field>
                        <label>Name</label>
                        <md-input id="name" name="name" type="name" value="{{ old('name') }}"
                                  placeholder="Name" required></md-input>
                    </md-field>
                    <md-field>
                        <label>Email</label>
                        <md-input id="email" name="email" type="email" value="{{ old('email') }}"
                                  placeholder="Email address" required></md-input>
                    </md-field>
                    <md-field>
                        @if ($errors->has('password'))
                            <label>
                                <strong>{{ $errors->first('password') }}</strong>
                            </label>
                        @else
                            <label>Password</label>
                        @endif
                        <md-input id="password" name="password" type="password" placeholder="Password" required></md-input>
                    </md-field>
                    <md-field>
                        <label>Password confirm</label>
                        <md-input id="password-confirm" name="password_confirmation" type="password" placeholder="Password confirm" required></md-input>
                    </md-field>

                    <md-divider style="margin-top: 10px;"></md-divider>

                    <div class="md-layout md-alignment-center-right" style="margin-top: 20px">
                        <md-button class="md-raised md-primary" type="submit">Register</md-button>
                        <a href="/login">
                            <md-button class="md-primary">Already have an account?</md-button>
                        </a>
                    </div>

                </form>
            </div>
            <div class="md-layout-item md-size-50 md-xsmall-hide">
                <div class="flex-box"
                     style="justify-content: center; align-items: center; flex-direction: column; padding: 15px; height: 100%;">
                    <img src="/images/sheep2.png" style="width: 260px; height: auto;">
                    <span class="md-caption" style="font-style: italic; padding-top: 15px">
                    @php
                        $quotes = [
                            "People, like sheep, tend to follow a leader - occasionally in the right direction. - Alexander Chase",
                            "A truly strong person does not need the approval of others any more than a lion needs the approval of sheep. - Vernon Howard",
                            "It's better to be a lion for a day than a sheep all your life. - Elizabeth Kenny",
                            "I'm a little bit more unusual so I consider myself as the black sheep. - Ann Wilson",
                            "It never troubles the wolf how many the sheep may be - Virgil",
                            "Democracy must be something more than two wolves and a sheep voting on what to have for dinner. - James Bovard",
                            "The mountain sheep are sweeter, But the valley sheep are fatter. We therefore deemed it meeter To carry off the latter. - Thomas Love Peacock",
                            "I don't want anybody suspecting I am some sheep and part of the Washington D.C. establishment. - George Nethercutt",
                            "It is madness for sheep to talk peace with a wolf. - Thomas Fuller",
                            "If someone wants a sheep, then that means that he exists - Antoine de Saint-Exupery",
                            "In things a moderation keep; Kings ought to shear, not skin, their sheep. - Robert Herrick",
                            "New Zealand is a country of thirty thousand million sheep, three million of whom think they are human. - Barry Humphries",
                            "If the freedom of speech is taken away then dumb and silent we may be led, like sheep to the slaughter. - George Washington",
                            "The seaman tells stories of winds, the ploughman of bulls; the soldier details his wounds, the shepherd his sheep. - Elegies",
                            "I was considered the black sheep of the family, neighbours didn't want their kids playing with me. - Michelle Pfeiffer",
                            "It is better to have a lion at the head of an army of sheep, than a sheep at the head of an army of lions. - Daniel Defoe",
                            "You only get out of it what you put into it. If you are a sheep in this world, you're not going to get much out of it. - Greg Norman",
                            "Every man can tell how many goats or sheep he possesses, but not how many friends. - Marcus Tullius Cicero",
                            "A leap year Is never a good sheep year. - Old Saying",
                            "Ideas are easy. It's the execution of ideas that really separates the sheep from the goats. - Sue Grafton",
                            "It is the duty of a good shepherd to shear his sheep, not to skin them. - Tiberius",
                            "After the accident Black Sheep was pretty much at an end. - Lou Gramm",
                        ];
                        echo $quotes[array_rand($quotes, 1)];
                    @endphp
                </span>
                </div>
            </div>
        </div>
    </div>
@endsection
