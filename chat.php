<?php

require "include/index.php";

checkSessionUser(true);

$title = "Chat";

require "views/home/header.php";
?>

<h1 class="text-lg font-meduim">Messages</h1>

<script>
function defineChat() {
    return {
        ids: new Set(),
        chats: [],
        get lid() {
            if (!this.chats.length) return null
           return this.chats[0].mid;
        },
        init() {
            const id = setInterval(async () => {
                try {
                    const res = await fetch("api.php" + (this.lid ? '?i='+this.lid : ''), {
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json"
                        },
                    });
                    const data = await res.json();
                    for(const d of data.reverse()) {
                        if (!this.ids.has(d.mid)) {
                            this.chats.unshift(d);
                            this.ids.add(d.mid);
                        }
                    }
                } catch(e) {
                    console.log(e);
                } finally {
                    if (this.chats.length > 15) {
                        this.chats.splice(15,);
                    }
                }
            }, 2500);
            window.onbeforeunload = () => {
                clearInterval(id);
            };
        },
        msg: '',
        async send() {
            try {
                await fetch("api.php", {
                    method: "post",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        uid: "<?= Session::getUser("uid") ?>",
                        text: this.msg
                    })
                });
                this.msg = "";
            } catch(e) {
                // console.log(e);
            }
        }
    }
}

</script>

<div x-data="defineChat()" class="grid grid-cols-1 gap-2">
    <form @submit.prevent="send" class="flex-grow flex justify-end items-stretch px-4 py-6" method="post" action="#">
        <input x-model.trim="msg" class="flex-grow px-2 py-1.5 border outline-none rounded-l-md" placeholder="Message..." type="text" required name="message">
        <input type="hidden" class="hidden" name="token" value="<?= Session::generateToken() ?>">
        <input type="text" class="hidden" name="uid" value="<?= $data[
            "uid"
        ] ?>">
        <button type="submit" class="px-3 outline-none bg-blue-500 text-white rounded-r-md">
            SEND
        </button>
    </form>

    <div x-show="!chats.length" class="text-slate-600 p-4">No new messages yet.</div>

    <template x-for="(n, i) in chats" x-key="i">
        <div class="flex items-center px-3 py-1 space-x-4">
            <div>
                <img :src="n.avatar.startsWith('data:image') ? n.avatar : `assets/img/${n.avatar}`" class="w-8 rounded-full border">
            </div>
            <a :href="`user.php?i=${n.uid}`" x-text="`@${n.username}`" class="hover:text-blue-600"></a>
            <div>&rarr;</div>
            <div>
                <div class="bg-slate-100 rounded-md px-3 py-1" x-text="n.text"></div>
            </div>
        </div>
    </template>
</div>

<?php require "views/home/footer.php"; ?>
