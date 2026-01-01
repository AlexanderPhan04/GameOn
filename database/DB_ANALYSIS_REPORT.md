# 📊 BÁO CÁO PHÂN TÍCH DATABASE - GAME_ON
> Phân tích bởi: Senior Backend Engineer & Database Architect  
> Ngày: 01/01/2026  
> Hệ thống: Nền tảng Esports / Mạng xã hội cho Gamer

---

## 📋 TỔNG QUAN DATABASE

### Danh sách 24 bảng nghiệp vụ:
| # | Tên bảng | Domain | Ghi chú |
|---|----------|--------|---------|
| 1 | users | User Core | Bảng trung tâm |
| 2 | user_profiles | User Core | Tách profile |
| 3 | user_activities | User Core | Tách activity |
| 4 | user_inventory | Marketplace | Kho đồ user |
| 5 | games | Esports | Game |
| 6 | teams | Esports | Team |
| 7 | team_members | Esports | Thành viên team |
| 8 | tournaments | Esports | Giải đấu |
| 9 | chat_conversations | Chat | Cuộc trò chuyện |
| 10 | chat_messages | Chat | Tin nhắn |
| 11 | chat_participants | Chat | Người tham gia |
| 12 | posts | Social | Bài viết |
| 13 | post_comments | Social | Bình luận |
| 14 | post_comment_likes | Social | Like comment |
| 15 | post_comment_reactions | Social | Reaction comment |
| 16 | post_likes | Social | Like post |
| 17 | post_media | Social | Media post |
| 18 | post_mentions | Social | Mention |
| 19 | post_reactions | Social | Reaction post |
| 20 | marketplace_products | Marketplace | Sản phẩm |
| 21 | marketplace_orders | Marketplace | Đơn hàng |
| 22 | marketplace_order_items | Marketplace | Chi tiết đơn |
| 23 | transactions | Payment | Giao dịch |
| 24 | donations | Payment | Donate |
| 25 | honor_events | Honor | Sự kiện vinh danh |
| 26 | honor_votes | Honor | Bình chọn |

---

## 🔴 PHẦN 1: ĐIỂM YẾU VÀ SAI LẦM THIẾT KẾ

### 1.1. ENUM CỨNG - KHÓ MỞ RỘNG

| Bảng | Cột | Giá trị ENUM | Vấn đề |
|------|-----|--------------|--------|
| `users` | `status` | `active,suspended,banned,deleted` | OK, ít thay đổi |
| `users` | `user_role` | varchar(20) nhưng logic như enum | ✅ Đã fix đúng |
| `games` | `format` | `individual,team` | ⚠️ Thiếu `mixed` |
| `games` | `status` | `active,maintenance,discontinued` | OK |
| `teams` | `status` | `active,inactive,disbanded` | OK |
| `team_members` | `role` | `member,captain` | ⚠️ Thiếu `coach`, `manager`, `sub` |
| `team_members` | `status` | `active,inactive,kicked,left` | OK |
| `tournaments` | `format` | `single_elimination,double_elimination,round_robin,swiss_system` | ⚠️ Thiếu `bracket`, `league` |
| `tournaments` | `competition_type` | `individual,team` | ⚠️ Thiếu `mixed` (cá nhân + team) |
| `tournaments` | `status` | `registration,ongoing,completed,cancelled` | ⚠️ Thiếu `draft`, `paused` |
| `chat_conversations` | `type` | `private,group` | ⚠️ Thiếu `team_chat`, `tournament_chat` |
| `chat_messages` | `type` | `text,image,file,system` | ⚠️ Thiếu `video`, `voice`, `sticker` |
| `chat_participants` | `role` | `member,admin,owner` | OK |
| `marketplace_products` | `type` | `theme,sticker,game_item,donation` | ⚠️ Thiếu `subscription`, `badge` |
| `marketplace_products` | `category` | 8 giá trị | ⚠️ Quá nhiều, nên tách bảng categories |
| `marketplace_products` | `rarity` | `common,uncommon,rare,epic,legendary` | OK (game standard) |
| `marketplace_orders` | `status` | 5 giá trị | OK |
| `donations` | `status` | 4 giá trị | OK |
| `transactions` | `type` | `deposit,withdrawal,purchase,donation,refund` | ⚠️ Thiếu `transfer`, `bonus` |
| `honor_events` | `mode` | `free,event` | ⚠️ Không rõ nghĩa |
| `honor_events` | `target_type` | `player,team,tournament,game` | OK |
| `honor_votes` | `vote_type` | Duplicate với target_type | ⚠️ Thừa |
| `honor_votes` | `voter_role` | `viewer,player,admin,super_admin` | ⚠️ Nên lấy từ users.user_role |
| `post_reactions` | `type` | `like,love,haha,wow,sad,angry` | OK (Facebook standard) |
| `post_media` | `type` | `image,video` | ⚠️ Thiếu `gif`, `audio` |
| `user_activities` | `online_status` | `online,away,busy,offline` | OK |
| `user_inventory` | `equipment_slot` | `theme,avatar_frame,sticker,emote` | OK |

**🔥 Nghiêm trọng nhất:**
1. `team_members.role` - Thiếu vai trò esports quan trọng
2. `tournaments.competition_type` - Thiếu mixed mode
3. `marketplace_products.category` - Nên tách bảng riêng

---

### 1.2. JSON DÙNG SAI CHỖ HOẶC KHÔNG CẦN THIẾT

| Bảng | Cột JSON | Đánh giá | Lý do |
|------|----------|----------|-------|
| `games` | `format_metadata` | ⚠️ Cần review | Không rõ dùng để làm gì, có thể tách cột |
| `tournaments` | `prize_distribution` | ✅ OK | Cấu trúc linh hoạt cho giải thưởng |
| `tournaments` | `rules` | ✅ OK | Luật thi đấu phức tạp, JSON phù hợp |
| `marketplace_products` | `images` | ✅ OK | Array ảnh sản phẩm |
| `marketplace_products` | `metadata` | ⚠️ Cần review | Quá generic, không rõ dùng gì |
| `chat_messages` | `reactions` | ⚠️ Sai | Nên tách bảng `chat_message_reactions` |
| `user_inventory` | `custom_data` | ⚠️ Cần review | Không rõ schema |

**🔥 Nghiêm trọng:**
- `chat_messages.reactions` - Không thể query hiệu quả, không thể count theo reaction type

---

### 1.3. FOREIGN KEY THIẾU HOẶC DƯ THỪA

#### ❌ THIẾU FOREIGN KEY:

| Bảng | Cột | Nên FK đến | Vấn đề |
|------|-----|------------|--------|
| `marketplace_products` | `game_id` | `games.id` | ⚠️ Đang là VARCHAR, không FK! |
| `honor_votes` | `voted_item_id` | Polymorphic | ⚠️ Không có FK, dễ orphan data |
| `tournaments` | - | `esports_users` | ⚠️ Thiếu bảng đăng ký giải đấu! |
| `posts` | `visibility_include_ids` | - | ⚠️ TEXT chứa IDs, không FK |
| `posts` | `visibility_exclude_ids` | - | ⚠️ TEXT chứa IDs, không FK |

#### ✅ FOREIGN KEY ĐÃ TỐT:
- `users` → `user_profiles`, `user_activities` (1:1)
- `teams` → `games`, `users` 
- `tournaments` → `games`, `users`
- `marketplace_orders` → `transactions` 
- `donations` → `transactions`

---

### 1.4. NAMING GÂY HIỂU NHẦM LOGIC

| Bảng/Cột | Vấn đề | Đề xuất |
|----------|--------|---------|
| `posts.media_path` | Có `post_media` rồi, cột này thừa? | Xóa hoặc migrate sang post_media |
| `teams.is_active` + `teams.status` | Trùng ý nghĩa | Bỏ `is_active`, dùng `status` |
| `games.is_active` + `games.status` | Trùng ý nghĩa | Bỏ `is_active`, dùng `status` |
| `tournaments.is_active` | Có `status` rồi | Bỏ `is_active` |
| `user_profiles.is_verified` vs `users.is_verified_gamer` | Trùng ý nghĩa | Chọn 1, bỏ 1 |
| `marketplace_orders.order_code` | Index gọi là `order_id` | Đổi tên index |
| `chat_messages.is_deleted` + `deleted_at` | Thừa | Dùng `deleted_at` (SoftDeletes) |
| `chat_messages.is_edited` + `edited_at` | Thừa | Dùng `edited_at` |
| `honor_votes.voted_user_id` | Không rõ, có thể vote team/tournament | Đổi thành nullable hoặc tách |

---

### 1.5. NHỮNG CHỖ SẼ GÂY BUG HOẶC KHÓ SCALE

#### 🔴 CRITICAL - Gây bug ngay:

1. **`marketplace_products.game_id` là VARCHAR**
   - Không có FK constraint
   - Có thể chứa giá trị không hợp lệ
   - Query JOIN sẽ chậm

2. **`posts.visibility_include_ids` / `visibility_exclude_ids` là TEXT**
   - Chứa danh sách user IDs dạng comma-separated?
   - Không thể query hiệu quả
   - Không có FK integrity

3. **`honor_votes.voted_item_id` polymorphic không có FK**
   - Dễ orphan data khi xóa team/tournament/game
   - Không có referential integrity

#### 🟠 HIGH - Khó scale khi data lớn:

1. **`chat_messages.reactions` là JSON**
   - Không thể index
   - Query "top reactions" rất chậm
   - Không thể pagination

2. **`posts` bảng monolithic**
   - `likes_count`, `comments_count`, `shares_count` denormalized
   - Race condition khi update concurrent
   - Cần queue/event để sync

3. **Không có bảng `tournament_registrations`**
   - Không track được ai đăng ký giải
   - Không có lịch sử đăng ký

4. **Không có bảng `tournament_matches`**
   - Không track được kết quả từng trận
   - Không có bracket visualization

#### 🟡 MEDIUM - Technical debt:

1. **`user_inventory.product_id` nullable**
   - Inventory item không có product?
   - Logic không rõ ràng

2. **`esports_users` table không thấy trong schema**
   - Migration có tạo nhưng có thể đã bị drop
   - Cần kiểm tra

---

### 1.6. DOMAIN BỊ TRỘN

| Vấn đề | Chi tiết |
|--------|----------|
| `users` chứa cả auth + role + verification | Nên tách: `users` (auth), `user_roles` (RBAC) |
| `teams` chứa cả info + captain logic | Captain nên là relation trong `team_members` |
| `tournaments` ôm quá nhiều | Thiếu: registrations, matches, brackets, results |
| `honor_votes` polymorphic nhưng thiếu type table | Khó mở rộng, khó maintain |

---

## 📊 PHẦN 2: PHÂN LOẠI BẢNG

### ✅ GIỮ NGUYÊN (Thiết kế tốt):
| Bảng | Lý do |
|------|-------|
| `users` | Core, đã tách profile/activity |
| `user_profiles` | 1:1 với users, clean |
| `user_activities` | 1:1 với users, clean |
| `transactions` | Normalized, có FK |
| `marketplace_orders` | Linked với transactions |
| `marketplace_order_items` | Normalized |
| `post_comments` | Self-referencing cho nested |
| `post_likes` | Simple pivot |
| `post_reactions` | Simple pivot |
| `post_comment_likes` | Simple pivot |
| `post_comment_reactions` | Simple pivot |
| `post_media` | Tách media riêng |
| `post_mentions` | Simple pivot |
| `chat_conversations` | Clean |
| `chat_participants` | Clean pivot |
| `team_members` | Clean pivot |

### ⚠️ GIỮ NHƯNG CẦN CHỈNH:
| Bảng | Cần chỉnh | Chi tiết |
|------|-----------|----------|
| `games` | Bỏ `is_active` | Dùng `status` |
| `teams` | Bỏ `is_active`, xử lý captain | Dùng `status`, captain qua team_members |
| `tournaments` | Bỏ `is_active`, thêm states | Dùng `status` |
| `posts` | Bỏ `media_path`, xử lý visibility | Dùng post_media, tách visibility |
| `marketplace_products` | Fix `game_id`, tách category | FK đến games, bảng categories |
| `chat_messages` | Tách reactions, bỏ is_deleted/is_edited | Dùng SoftDeletes chuẩn |
| `donations` | OK nhưng review duplicate với transactions | Có thể merge logic |
| `user_inventory` | Review nullable product_id | Clarify logic |
| `honor_events` | Clarify mode enum | Rename hoặc document |
| `honor_votes` | Fix polymorphic, bỏ duplicate enum | Tách bảng hoặc dùng morph |

### ❌ NÊN TÁCH / THÊM MỚI:
| Hành động | Bảng | Lý do |
|-----------|------|-------|
| **THÊM** | `tournament_registrations` | Track đăng ký giải |
| **THÊM** | `tournament_matches` | Track từng trận đấu |
| **THÊM** | `tournament_results` | Kết quả, xếp hạng |
| **THÊM** | `chat_message_reactions` | Tách từ JSON |
| **THÊM** | `product_categories` | Tách từ ENUM |
| **THÊM** | `post_visibility_users` | Tách từ TEXT fields |
| **TÁCH** | `user_verifications` | Từ users.is_verified_gamer |

---

## 🔥 PHẦN 3: TOP 5 LỖI NGHIÊM TRỌNG NHẤT

### 1️⃣ **MARKETPLACE_PRODUCTS.GAME_ID LÀ VARCHAR - KHÔNG FK**
- **Mức độ:** 🔴 CRITICAL
- **Vấn đề:** Không có referential integrity, có thể chứa data rác
- **Hậu quả:** Query chậm, data không consistent
- **Fix:** Migration đổi sang BIGINT + FK

### 2️⃣ **THIẾU BẢNG TOURNAMENT_REGISTRATIONS & MATCHES**
- **Mức độ:** 🔴 CRITICAL  
- **Vấn đề:** Không track được ai tham gia giải, không có kết quả trận
- **Hậu quả:** Feature tournament không hoàn chỉnh
- **Fix:** Tạo 2-3 bảng mới

### 3️⃣ **CHAT_MESSAGES.REACTIONS LÀ JSON**
- **Mức độ:** 🟠 HIGH
- **Vấn đề:** Không query được, không index được
- **Hậu quả:** Performance tệ khi scale
- **Fix:** Tạo bảng chat_message_reactions

### 4️⃣ **POSTS.VISIBILITY_*_IDS LÀ TEXT**
- **Mức độ:** 🟠 HIGH
- **Vấn đề:** Chứa IDs dạng string, không FK
- **Hậu quả:** Không thể query "posts visible to user X"
- **Fix:** Tạo bảng post_visibility_users

### 5️⃣ **DUPLICATE FLAGS: IS_ACTIVE + STATUS**
- **Mức độ:** 🟡 MEDIUM
- **Vấn đề:** games, teams, tournaments có cả 2
- **Hậu quả:** Logic confusing, có thể inconsistent
- **Fix:** Bỏ is_active, chỉ dùng status

---

## 🗺️ PHẦN 4: ROADMAP REFACTOR - 5 MIGRATIONS

### Migration 1: Fix Critical Data Types
```
2026_01_01_000001_fix_marketplace_products_game_id.php
```
**Nội dung:**
- Đổi `marketplace_products.game_id` từ VARCHAR → BIGINT UNSIGNED
- Thêm FK constraint đến `games.id`
- Migrate data: convert string to int (nếu có)

### Migration 2: Add Tournament Features
```
2026_01_01_000002_create_tournament_registrations_table.php
2026_01_01_000003_create_tournament_matches_table.php
```
**Nội dung:**
- Tạo `tournament_registrations` (user_id/team_id, tournament_id, status, registered_at)
- Tạo `tournament_matches` (tournament_id, round, match_number, participant_1, participant_2, winner, scores, played_at)
- FK constraints đầy đủ

### Migration 3: Fix Chat Reactions
```
2026_01_01_000004_create_chat_message_reactions_table.php
2026_01_01_000005_migrate_chat_reactions_from_json.php
```
**Nội dung:**
- Tạo `chat_message_reactions` (message_id, user_id, type, created_at)
- Migrate data từ JSON column
- Có thể giữ JSON column để backward compatible

### Migration 4: Fix Post Visibility
```
2026_01_01_000006_create_post_visibility_users_table.php
2026_01_01_000007_migrate_post_visibility_data.php
```
**Nội dung:**
- Tạo `post_visibility_users` (post_id, user_id, type: include/exclude)
- Migrate data từ TEXT columns
- Deprecate old columns (không xóa ngay)

### Migration 5: Cleanup Duplicate Flags
```
2026_01_01_000008_remove_is_active_from_tables.php
```
**Nội dung:**
- Bỏ `is_active` từ games, teams, tournaments
- Update logic để chỉ dùng `status`
- Đảm bảo `status` có giá trị tương ứng

---

## 📝 PHẦN 5: CHI TIẾT MIGRATION CODE

### Migration 1: Fix game_id trong marketplace_products

```php
<?php
// 2026_01_01_000001_fix_marketplace_products_game_id.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Add new column
        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->unsignedBigInteger('game_id_new')->nullable()->after('game_id');
        });

        // Step 2: Migrate data (convert string to int if valid)
        DB::statement('
            UPDATE marketplace_products 
            SET game_id_new = CAST(game_id AS UNSIGNED)
            WHERE game_id IS NOT NULL 
            AND game_id REGEXP "^[0-9]+$"
            AND EXISTS (SELECT 1 FROM games WHERE id = CAST(marketplace_products.game_id AS UNSIGNED))
        ');

        // Step 3: Drop old column, rename new
        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->dropColumn('game_id');
        });

        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->renameColumn('game_id_new', 'game_id');
        });

        // Step 4: Add FK constraint
        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->foreign('game_id')
                  ->references('id')
                  ->on('games')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->dropForeign(['game_id']);
            $table->string('game_id', 255)->nullable()->change();
        });
    }
};
```

### Migration 2: Tournament Registrations

```php
<?php
// 2026_01_01_000002_create_tournament_registrations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournament_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('participant_type', ['individual', 'team'])->default('individual');
            $table->enum('status', ['pending', 'approved', 'rejected', 'withdrawn', 'checked_in'])
                  ->default('pending');
            $table->integer('seed')->nullable()->comment('Seeding/ranking for bracket');
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Constraints
            $table->unique(['tournament_id', 'user_id'], 'unique_user_registration');
            $table->unique(['tournament_id', 'team_id'], 'unique_team_registration');
            $table->index(['tournament_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournament_registrations');
    }
};
```

### Migration 3: Tournament Matches

```php
<?php
// 2026_01_01_000003_create_tournament_matches_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournament_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
            $table->string('round_name', 50)->comment('Round of 16, Quarterfinal, etc.');
            $table->integer('round_number')->default(1);
            $table->integer('match_number')->default(1);
            $table->enum('bracket_type', ['winners', 'losers', 'grand_final'])->default('winners');
            
            // Participants (polymorphic - can be user or team)
            $table->unsignedBigInteger('participant_1_id')->nullable();
            $table->string('participant_1_type', 20)->nullable()->comment('user or team');
            $table->unsignedBigInteger('participant_2_id')->nullable();
            $table->string('participant_2_type', 20)->nullable()->comment('user or team');
            
            // Results
            $table->unsignedBigInteger('winner_id')->nullable();
            $table->string('winner_type', 20)->nullable();
            $table->integer('score_1')->nullable();
            $table->integer('score_2')->nullable();
            $table->json('game_scores')->nullable()->comment('Scores per game/map');
            
            // Status & Schedule
            $table->enum('status', ['scheduled', 'live', 'completed', 'cancelled', 'walkover'])
                  ->default('scheduled');
            $table->datetime('scheduled_at')->nullable();
            $table->datetime('started_at')->nullable();
            $table->datetime('ended_at')->nullable();
            
            // Stream & VOD
            $table->string('stream_url')->nullable();
            $table->string('vod_url')->nullable();
            
            $table->timestamps();

            // Indexes
            $table->index(['tournament_id', 'round_number', 'match_number']);
            $table->index(['tournament_id', 'status']);
            $table->index(['scheduled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournament_matches');
    }
};
```

### Migration 4: Chat Message Reactions

```php
<?php
// 2026_01_01_000004_create_chat_message_reactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_message_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')
                  ->constrained('chat_messages')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->string('type', 50)->comment('emoji or reaction type');
            $table->timestamps();

            // Each user can only have one reaction per message
            $table->unique(['message_id', 'user_id']);
            $table->index(['message_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_message_reactions');
    }
};
```

### Migration 5: Post Visibility Users

```php
<?php
// 2026_01_01_000006_create_post_visibility_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_visibility_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->enum('type', ['include', 'exclude'])->default('include');
            $table->timestamps();

            $table->unique(['post_id', 'user_id']);
            $table->index(['user_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_visibility_users');
    }
};
```

---

## ✅ KẾT LUẬN

Database hiện tại đã có nền tảng khá tốt với:
- User/Profile/Activity tách riêng ✓
- Transactions/Orders/Donations có liên kết ✓
- Chat/Post features đầy đủ cơ bản ✓

**Cần ưu tiên fix:**
1. `marketplace_products.game_id` - Data integrity
2. Tournament registrations/matches - Core feature
3. Chat reactions - Performance
4. Post visibility - Security/Privacy
5. Duplicate flags cleanup - Code quality

**Timeline đề xuất:**
- Tuần 1: Migration 1 (fix game_id)
- Tuần 2-3: Migration 2-3 (tournament features)
- Tuần 4: Migration 4 (chat reactions)
- Tuần 5: Migration 5-6 (post visibility + cleanup)

---
*Report generated: 01/01/2026*
