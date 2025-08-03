# Map Image Uploader Feature

This feature allows users to upload and manage map images in the GIS Crop Land Use Mapping application.

## Features

### 1. **Dashboard with Tabs**
The dashboard now includes three tabs:
- **Overview**: Shows statistics and welcome message
- **Upload Map**: Upload new map images directly from the dashboard
- **Map Gallery**: View all uploaded map images

### 2. **Map Image Upload**
- Upload images in common formats (JPEG, PNG, GIF, SVG)
- Maximum file size: 10MB
- Add title and description for each map
- Real-time image preview before upload
- Automatic file organization and storage

### 3. **Map Gallery**
- Grid view of all uploaded map images
- Shows title, description, uploader, and upload date
- Quick view and delete options
- Responsive design for different screen sizes

### 4. **Individual Map View**
- Detailed view of each map image
- Full-size image display
- Complete metadata information
- Delete option for image owners

## Database Structure

The feature uses a `map_images` table with the following fields:
- `id`: Primary key
- `title`: Map title (required)
- `description`: Optional description
- `filename`: Generated unique filename
- `original_name`: Original uploaded filename
- `file_path`: Storage path
- `file_size`: File size in bytes
- `mime_type`: File MIME type
- `user_id`: Foreign key to users table
- `created_at`, `updated_at`: Timestamps

## Routes

- `GET /dashboard`: Main dashboard with tabs
- `GET /maps`: List all map images
- `GET /maps/create`: Upload form (standalone)
- `POST /maps`: Handle upload
- `GET /maps/{id}`: View individual map
- `DELETE /maps/{id}`: Delete map (owner only)

## File Storage

- Images are stored in `storage/app/public/map-images/`
- Accessible via public URL through symbolic link
- Random filename generation for security
- Original filenames preserved in database

## Security Features

- Authentication required for all operations
- Users can only delete their own uploads
- File type validation
- File size limits
- Secure file storage with random filenames

## Usage

1. **Login** to your account
2. **Navigate** to the Dashboard
3. **Click** the "Upload Map" tab
4. **Fill** in the title and optional description
5. **Select** your map image file
6. **Preview** the image before uploading
7. **Click** "Upload Map" to save
8. **View** your uploaded maps in the "Map Gallery" tab

## Technical Notes

- Built with Laravel 11
- Uses Tailwind CSS for styling
- JavaScript for tab functionality and image preview
- Responsive design for mobile and desktop
- File uploads handled securely with Laravel's storage system
