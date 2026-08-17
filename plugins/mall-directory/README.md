# Mall Directory Plugin

Interactive mall directory with store listings, floor plan map, and filtering capabilities.

## Features

✨ **Interactive Floor Plan Map**
- Hover-based store markers with tooltips
- Shows store logo and category on hover
- Sticky map position on desktop

🏪 **Store Management**
- Custom post type for stores
- Easy logo upload
- Position stores on the floor plan with coordinates
- Organize stores by categories

🔍 **Advanced Filtering**
- Filter stores by category with checkboxes
- Real-time search functionality
- Shows matching stores on map and in list

📱 **Responsive Design**
- Works on desktop, tablet, and mobile
- Optimized layout for all screen sizes

## Installation

1. Upload the `mall-directory` folder to `/wp-content/plugins/`
2. Activate the plugin from WordPress admin
3. Go to **Stores** in the left menu to manage stores
4. Create a page and add the shortcode: `[mall_directory]`

## Usage

### Creating a Store

1. Go to **Stores** → **Add New**
2. Enter the store name and description (optional)
3. In the **Store Details** metabox:
   - Upload a store logo (will appear on map and in list)
   - Set the X and Y coordinates on the floor plan
4. Select store categories
5. Publish

### Setting Store Coordinates

The floor plan is 1200px wide × 800px tall by default.

**To find coordinates:**
- Desktop: You can click on the floor plan image on the frontend to see coordinates
- Or manually estimate based on the position (top-left is 0,0)

Example positions on the dummy floor plan:
- Unit 1: X=225, Y=165
- Unit 2: X=505, Y=165
- Unit 5: X=225, Y=415
- Unit 10: X=785, Y=665

### Adding the Directory to a Page

Create or edit a page and add this shortcode:

```
[mall_directory]
```

You can also specify a custom floor plan image:

```
[mall_directory floorplan="https://example.com/your-floorplan.jpg"]
```

## Customizing the Floor Plan

### To Update the Floor Plan Image

1. **Option 1:** Replace the dummy image
   - Replace `/assets/images/dummy-floorplan.png` with your floor plan

2. **Option 2:** Use a custom floor plan per page
   - Use the shortcode parameter: `[mall_directory floorplan="URL_TO_YOUR_IMAGE"]`

### Floor Plan Requirements

- **Format:** JPG, PNG, or SVG
- **Recommended size:** 1200×800px (can be larger)
- **Scale:** Coordinates should match your image dimensions

## Asking the Client for Assets

Tell them to provide:

1. **Floor Plan Image**
   - High-resolution image (minimum 2000×2000px recommended)
   - If SVG: with clear dimensions and unit identifiers
   - If PNG/JPG: exact pixel dimensions

2. **Store Data Spreadsheet** with columns:
   - Store Name
   - Category (e.g., Cafe, Fashion, Services, Restaurant)
   - Store Logo/Image
   - Brief Description (optional)
   - Building (e.g., "Level 2, Section A") OR X,Y coordinates
   - Stall Number (optional — for stores occupying multiple or specific stalls, e.g. "Stall # 4 & 5 – 18")

3. **Optional:**
   - Store website/links
   - Phone number
   - Hours of operation

## File Structure

```
mall-directory/
├── mall-directory.php           # Main plugin file
├── includes/
│   ├── cpt-register.php         # Custom post type & taxonomy
│   ├── metabox.php              # Admin metabox
│   └── shortcode.php            # Frontend shortcode
├── admin/
│   ├── js/metabox.js            # Admin logo upload
│   └── css/metabox.css          # Admin styling
├── frontend/
│   ├── js/directory.js          # Interactive map & filters
│   ├── css/directory.css        # Frontend styling
│   └── views/
│       └── directory-shortcode.php  # Frontend template
└── assets/
    └── images/
        └── dummy-floorplan.png  # Placeholder floor plan
```

## Upgrading the Floor Plan

When you get the real floor plan from the client:

1. Note the exact pixel dimensions (e.g., 1600×1000)
2. Replace the dummy image in `/assets/images/`
3. Recalculate store coordinates to match new dimensions
4. Update store positions in the admin panel

## Troubleshooting

**Stores not showing on map?**
- Check that X and Y coordinates are set in the metabox
- Verify coordinates are within the floor plan dimensions

**Logo not displaying?**
- Ensure the media file was uploaded correctly
- Check that you have an active media library

**Categories not filtering?**
- Assign at least one category to the stores
- Refresh the page after assigning categories

## Future Enhancements

Potential features to add:
- Store hours display
- Contact information
- Social media links
- Ratings/reviews
- Store type icons
- Floor level selector
- Advanced map coordinates picker
- Store details modal popup

## Version

Current Version: 1.0.0

## License

GPL v2 or later
