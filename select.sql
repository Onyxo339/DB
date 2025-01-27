SELECT 
    v.name AS vendor_name, 
    COUNT(p.id) AS product_count
FROM 
    vendor v
LEFT JOIN 
    product p ON v.id = p.vendor_id
GROUP BY 
    v.id, v.name
ORDER BY 
    product_count DESC
LIMIT 10;
