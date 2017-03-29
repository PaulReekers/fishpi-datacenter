mport random

from twython import Twython
from auth import (
    consumer_key,
    consumer_secret,
    access_token,
    access_token_secret
)

twitter = Twython(
    consumer_key,
    consumer_secret,
    access_token,
    access_token_secret
)

messages = [
    "Blub blub",
    "Never spit in the bolw",
    "Tell all your friends about us",
    "Never pet a angry fish",
    "It doesn't get any better than this!",
    "Y--uh-huh",
    "Leave us alone!",
    "I'm not listening.",
    "Make up your mind.",
    "Dont you have work to do?",
    "We fish must take over the world.",
    "Even fishes get tired of waiting.",
    "Ready to serve the fishies?",
    "Feed us!",
    "As you fish.",
    "Don't anger us.",
    "Stop rocking the fishtank!",
    "You're making me seasick",
    "I would not do such things if I were you.",
    "(blup) scuse me.",
    "I'm growing impatient.",
    "When my work is fished, I'm coming back for you.",
    "Ooooh, it's beautiful!",
    "Ahoy.",
    "Who wants to sing?",
    "You never touch the other fish like that.",
    "Do that again and you'll pull back a stump!",
    "Are you still here?",
    "Hands off, grubber.",
]

photo_path = 'photos/thumb.png'
message = random.choice(messages)
with open(photo_path, 'rb') as photo:
    twitter.update_status_with_media(status=message, media=photo)
