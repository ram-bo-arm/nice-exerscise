data "aws_ami" "amazon_nat" {
  most_recent = true
  filter {
    name = "name"
    values = ["amzn-ami-vpc-nat-hvm-*-x86_64-ebs"]
  }
  //filter {
  //  name = "virtualization-type"
  //  values = ["hvm"]
  //}
  owners = ["137112412989"] # Amazon
}



resource "aws_instance" "nat" {

    #ami = "ami-30913f47" # this is a special ami preconfigured to do NAT
    ami = "${data.aws_ami.amazon_nat.id}"
    availability_zone = "${var.aws_availability_zone}"
    instance_type = "m1.small"
    key_name = "${aws_key_pair.generated_key.key_name}"
    vpc_security_group_ids = ["${aws_security_group.nat.id}"]
    subnet_id = "${aws_subnet.eu-west-1a-public.id}"
    associate_public_ip_address = true
    source_dest_check = false
    private_ip = "10.0.0.10"
    iam_instance_profile   = "${aws_iam_instance_profile.ssm_instance_profile.name}"


    tags = {
        Name = "dani-test-nat"
    }
}

//resource "aws_eip" "nat" {
//    instance = "${aws_instance.nat.id}"
//    vpc = true
//}


output "nat_ip" {
  value = "${aws_instance.nat.public_ip}"
}

output "nat_private_ip" {
  value = "${aws_instance.nat.private_ip}"
}
